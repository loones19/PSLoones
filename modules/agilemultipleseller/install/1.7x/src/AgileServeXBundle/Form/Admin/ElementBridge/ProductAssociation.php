<?php
///-build_id: 2018051408.4945
/// This source file is subject to the Software License Agreement that is bundled with this 
/// package in the file license.txt, or you can get it here
/// http://addons-modules.com/en/content/3-terms-and-conditions-of-use
///
/// @copyright  2009-2016 Addons-Modules.com
///  If you need open code to customize or merge code with othe modules, please contact us.
namespace AgileServeXBundle\Form\Admin\ElementBridge;

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use PrestaShop\PrestaShop\Adapter\Configuration;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class ProductAssociation extends CommonAbstractType
{
    private $router;
    private $context;
    private $translator;
    private $nested_categories;
    private $productAdapter;
    private $configuration;
	private $manufacturers;

	public function __construct($translator, $legacyContext, $router, $categoryDataProvider, $productDataProvider,$manufacturerDataProvider)
    {
        $this->context = $legacyContext;
        $this->translator = $translator;
        $this->router = $router;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->productDataProvider = $productDataProvider;
		$this->manufacturerDataProvider = $manufacturerDataProvider;
		$this->configuration = new Configuration();

        $this->categories = $this->formatDataChoicesList($this->categoryDataProvider->getAllCategoriesName(), 'id_category');
        $this->nested_categories = $this->categoryDataProvider->getNestedCategories();
        $this->productAdapter = $this->productDataProvider;

		$this->manufacturers = $this->formatDataChoicesList(
			$this->manufacturerDataProvider->getManufacturers(false, 0, true, false, false, false, true),
			'id_manufacturer'
		);
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('categories', 'PrestaShopBundle\Form\Admin\Type\ChoiceCategoriesTreeType', array(
            'label' => $this->translator->trans('Associated categories', [], 'AdminProducts'),
            'list' => $this->nested_categories,
            'valid_list' => $this->categories,
            'multiple' => true,
        ))
        ->add('id_category_default', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
            'choices' =>  $this->categories,
            'choices_as_values' => true,
            'required' =>  true,
            'label' => $this->translator->trans('Default category', [], 'AdminProducts')
        ))
        ->add('new_category', 'PrestaShopBundle\Form\Admin\Category\SimpleCategory', array(
            'ajax' => true,
            'required' => false,
            'mapped' => false,
            'constraints' => [],
            'label' => $this->translator->trans('Add a new category', [], 'AdminProducts'),
            'attr' => ['data-action' => $this->router->generate('admin_category_simple_add_form')]
        ))
		->add('related_products', 'PrestaShopBundle\Form\Admin\Type\TypeaheadProductCollectionType', array(
			'remote_url' => $this->context->getAdminLink('', false).'ajax_products_list.php?forceJson=1&disableCombination=1&exclude_packs=0&excludeVirtuals=0&limit=20&q=%QUERY',
			'mapping_value' => 'id',
			'mapping_name' => 'name',
			'placeholder' => $this->translator->trans('Search and add a related product', [], 'AdminProducts'),
			'template_collection' => '<div class="title col-xs-10">%s</div><button type="button" class="btn btn-danger btn-sm delete"><i class="material-icons">delete</i></button>',
			'required' => false,
			'label' =>  $this->translator->trans('Accessories', [], 'AdminProducts')
		))
		->add('id_manufacturer', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
			'choices' => $this->manufacturers,
			'choices_as_values' => true,
			'required' => false,
			'label' => $this->translator->trans('Manufacturer', [], 'AdminProducts')
		));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
        });
    }

    public function getBlockPrefix()
    {
        return 'product_step1';
    }
}
