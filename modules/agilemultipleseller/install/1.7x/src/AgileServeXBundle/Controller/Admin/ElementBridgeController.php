<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
namespace AgileServeXBundle\Controller\Admin;

use AgileServeXBundle\Form\Admin\ElementBridge\ProductAssociation;
use AgileServeXBundle\Form\Admin\ElementBridge\ProductImages;
use AgileServeXBundle\Form\Admin\ElementBridge\ProductFeatures;

use PrestaShop\PrestaShop\Adapter\Warehouse\WarehouseDataProvider;
use PrestaShopBundle\Entity\AdminFilter;
use PrestaShopBundle\Service\DataProvider\StockInterface;
use PrestaShopBundle\Service\Hook\HookEvent;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

use Symfony\Component\HttpFoundation\Request;
use PrestaShopBundle\Service\TransitionalBehavior\AdminPagePreferenceInterface;
use PrestaShopBundle\Service\DataProvider\Admin\ProductInterface as ProductInterfaceProvider;
use PrestaShopBundle\Service\DataUpdater\Admin\ProductInterface as ProductInterfaceUpdater;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use PrestaShopBundle\Form\Admin\Product as ProductForms;
use PrestaShopBundle\Exception\DataUpdateException;
use PrestaShopBundle\Model\Product\AdminModelAdapter as ProductAdminModelAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;
use PrestaShopBundle\Service\Csv;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class ElementBridgeController extends FrameworkBundleAdminController
{
	
	public function formAction($id, Request $request)
	{
		$productAdapter = $this->container->get('prestashop.adapter.data_provider.product');
		$product = $productAdapter->getProduct($id);
		if (!$product || empty($product->id)) {
			return $this->redirectToRoute('admin_product_catalog');
		}

		$shopContext = $this->get('prestashop.adapter.shop.context');
		$legacyContextService = $this->get('prestashop.adapter.legacy.context');
		$legacyContext = $legacyContextService->getContext();
		$isMultiShopContext = count($shopContext->getContextListShopID()) > 1 ? true : false;

				$pagePreference = $this->container->get('prestashop.core.admin.page_preference_interface');
				if ($pagePreference->getTemporaryShouldUseLegacyPage('product')) {
			$legacyUrlGenerator = $this->container->get('prestashop.core.admin.url_generator_legacy');
						return $this->redirect($legacyUrlGenerator->generate('admin_product_form', array('id' => $id)), 302);
		}

		$response = new JsonResponse();
		$modelMapper = new ProductAdminModelAdapter(
			$product,
			$this->container->get('prestashop.adapter.legacy.context'),
			$this->container->get('prestashop.adapter.admin.wrapper.product'),
			$this->container->get('prestashop.adapter.tools'),
			$productAdapter,
			$this->container->get('prestashop.adapter.data_provider.supplier'),
			$this->container->get('prestashop.adapter.data_provider.warehouse'),
			$this->container->get('prestashop.adapter.data_provider.feature'),
			$this->container->get('prestashop.adapter.data_provider.pack'),
			$this->container->get('prestashop.adapter.shop.context')
			);
		$adminProductWrapper = $this->container->get('prestashop.adapter.admin.wrapper.product');

		$form = $this->createFormBuilder($modelMapper->getFormData())
			->add('id_product', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
			->add('step1', 'PrestaShopBundle\Form\Admin\Product\ProductInformation')
			->add('step2', 'PrestaShopBundle\Form\Admin\Product\ProductPrice')
			->add('step3', 'PrestaShopBundle\Form\Admin\Product\ProductQuantity')
			->add('step4', 'PrestaShopBundle\Form\Admin\Product\ProductShipping')
			->add('step5', 'PrestaShopBundle\Form\Admin\Product\ProductSeo')
			->add('step6', 'PrestaShopBundle\Form\Admin\Product\ProductOptions')
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				
								$_POST = $modelMapper->getModelData($form->getData(), $isMultiShopContext);

				$adminProductController = $adminProductWrapper->getInstance();
				$adminProductController->setIdObject($form->getData()['id_product']);
				$adminProductController->setAction('save');

																				if ($product = $adminProductController->postCoreProcess()) {
					$adminProductController->processSuppliers($product->id);
					$adminProductController->processFeatures($product->id);
					$adminProductController->processSpecificPricePriorities();
					foreach ($_POST['combinations'] as $combinationValues) {
						$adminProductWrapper->processProductAttribute($product, $combinationValues);
												$adminProductWrapper->processDependsOnStock($product, ($_POST['depends_on_stock'] == '1'), $combinationValues['id_product_attribute']);
					}
					$adminProductWrapper->processDependsOnStock($product, ($_POST['depends_on_stock'] == '1'));

															if (count($_POST['combinations']) === 0) {
						$adminProductWrapper->processQuantityUpdate($product, $_POST['qty_0']);
					}
					
					$adminProductWrapper->processProductOutOfStock($product, $_POST['out_of_stock']);
					$adminProductWrapper->processProductCustomization($product, $_POST['custom_fields']);
					$adminProductWrapper->processAttachments($product, $_POST['attachments']);

					$adminProductController->processWarehouses();

					$response->setData(['product' => $product]);
				}

				if ($request->isXmlHttpRequest()) {
					return $response;
				}
			} elseif ($request->isXmlHttpRequest()) {
				$response->setStatusCode(400);
				$response->setData($this->getFormErrorsForJS($form));
				return $response;
			}
		}

		$stockManager = $this->container->get('prestashop.core.data_provider.stock_interface');
		
		$warehouseProvider = $this->container->get('prestashop.adapter.data_provider.warehouse');
		
				if ($legacyContext->shop->getContext() == $shopContext->getShopContextGroupConstant()) {
			return $this->render('PrestaShopBundle:Admin/Product:formDisable.html.twig', ['showContentHeader' => false]);
		}

				$languages = $legacyContextService->getLanguages();

				if ($product->active) {
			$preview_url = $adminProductWrapper->getPreviewUrl($product);
			$preview_url_deactive = $adminProductWrapper->getPreviewUrlDeactivate($preview_url);
		} else {
			$preview_url_deactive = $adminProductWrapper->getPreviewUrl($product,false);
			$preview_url = $adminProductWrapper->getPreviewUrlDeactivate($preview_url_deactive);
		}

		return array(
			'form' => $form->createView(),
			'categories' => $this->get('prestashop.adapter.data_provider.category')->getCategoriesWithBreadCrumb(),
			'id_product' => $id,
			'has_combinations' => (isset($form->getData()['step3']['combinations']) && count($form->getData()['step3']['combinations']) > 0),
			'asm_globally_activated' => $stockManager->isAsmGloballyActivated(),
			'warehouses' => ($stockManager->isAsmGloballyActivated())? $warehouseProvider->getWarehouses() : [],
			'is_multishop_context' => $isMultiShopContext,
			'showContentHeader' => false,
			'preview_link' => $preview_url,
			'preview_link_deactivate' => $preview_url_deactive,
			'stats_link' => $legacyContextService->getAdminLink('AdminStats', true, ['module' => 'statsproduct', 'id_product' => $id]),
			'help_link' => $this->generateSidebarLink('AdminProducts'),
			'languages' => $languages,
			'default_language_iso' => $languages[0]['iso_code'],
			);
	}
	
	public function associationAction($id, Request $request)
		{
			$productAdapter = $this->container->get('prestashop.adapter.data_provider.product');
			$product = $productAdapter->getProduct($id);
			if (!$product || empty($product->id)) {
				return $this->redirectToRoute('admin_product_catalog');
			}

			$modelMapper = new ProductAdminModelAdapter(
				$product,
				$this->container->get('prestashop.adapter.legacy.context'),
				$this->container->get('prestashop.adapter.admin.wrapper.product'),
				$this->container->get('prestashop.adapter.tools'),
				$productAdapter,
				$this->container->get('prestashop.adapter.data_provider.supplier'),
				$this->container->get('prestashop.adapter.data_provider.warehouse'),
				$this->container->get('prestashop.adapter.data_provider.feature'),
				$this->container->get('prestashop.adapter.data_provider.pack'),
				$this->container->get('prestashop.adapter.shop.context')
				);
			$adminProductWrapper = $this->container->get('prestashop.adapter.admin.wrapper.product');

			$form = $this->createFormBuilder($modelMapper->getFormData())
			->add('id_product', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
			->add('step1', 'AgileServeXBundle\Form\Admin\ElementBridge\ProductAssociation')
			->getForm();

		$formview = $form->createView();


			$vars = array(
				'form' => $formview,
				'categories' => $this->get('prestashop.adapter.data_provider.category')->getCategoriesWithBreadCrumb(),
				'id_product' => $id
				);
			
			return  $this->render('AgileServeXBundle:Admin:ElementBridge/association.html.twig', $vars);
		}
		

	public function imagesAction($id, Request $request)
	{
		$productAdapter = $this->container->get('prestashop.adapter.data_provider.product');
		$product = $productAdapter->getProduct($id);
		if (!$product || empty($product->id)) {
			return $this->redirectToRoute('admin_product_catalog');
		}

		$modelMapper = new ProductAdminModelAdapter(
			$product,
			$this->container->get('prestashop.adapter.legacy.context'),
			$this->container->get('prestashop.adapter.admin.wrapper.product'),
			$this->container->get('prestashop.adapter.tools'),
			$productAdapter,
			$this->container->get('prestashop.adapter.data_provider.supplier'),
			$this->container->get('prestashop.adapter.data_provider.warehouse'),
			$this->container->get('prestashop.adapter.data_provider.feature'),
			$this->container->get('prestashop.adapter.data_provider.pack'),
			$this->container->get('prestashop.adapter.shop.context')
			);
		$adminProductWrapper = $this->container->get('prestashop.adapter.admin.wrapper.product');

		$form = $this->createFormBuilder($modelMapper->getFormData())
			->add('id_product', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
			->add('step1', 'AgileServeXBundle\Form\Admin\ElementBridge\ProductImages')
			->getForm();

		$formview = $form->createView();
	
		
		$vars = array(
			'form' => $formview,
			'id_product' => $id
			);
		
		return  $this->render('AgileServeXBundle:Admin:ElementBridge/images.html.twig', $vars);
	}
	
	public function featuresAction($id, Request $request)
	{
		$productAdapter = $this->container->get('prestashop.adapter.data_provider.product');
		$product = $productAdapter->getProduct($id);
		if (!$product || empty($product->id)) {
			return $this->redirectToRoute('admin_product_catalog');
		}

		$modelMapper = new ProductAdminModelAdapter(
			$product,
			$this->container->get('prestashop.adapter.legacy.context'),
			$this->container->get('prestashop.adapter.admin.wrapper.product'),
			$this->container->get('prestashop.adapter.tools'),
			$productAdapter,
			$this->container->get('prestashop.adapter.data_provider.supplier'),
			$this->container->get('prestashop.adapter.data_provider.warehouse'),
			$this->container->get('prestashop.adapter.data_provider.feature'),
			$this->container->get('prestashop.adapter.data_provider.pack'),
			$this->container->get('prestashop.adapter.shop.context')
			);
		$adminProductWrapper = $this->container->get('prestashop.adapter.admin.wrapper.product');

		$form = $this->createFormBuilder($modelMapper->getFormData())
			->add('id_product', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
			->add('step1', 'AgileServeXBundle\Form\Admin\ElementBridge\ProductFeatures')
			->getForm();

		$formview = $form->createView();
		
				
		$vars = array(
			'form' => $formview,
			'id_product' => $id
			);
		
		return  $this->render('AgileServeXBundle:Admin:ElementBridge/features.html.twig', $vars);
	}
	
	
}
