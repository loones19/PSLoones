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

class ProductImages extends CommonAbstractType
{
    private $router;
    private $context;
    private $translator;
    private $nested_categories;
    private $productAdapter;
    private $configuration;

    public function __construct($translator, $legacyContext, $router, $categoryDataProvider, $productDataProvider)
    {
        $this->context = $legacyContext;
        $this->translator = $translator;
        $this->router = $router;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->productDataProvider = $productDataProvider;
        $this->configuration = new Configuration();

        $this->categories = $this->formatDataChoicesList($this->categoryDataProvider->getAllCategoriesName(), 'id_category');
        $this->nested_categories = $this->categoryDataProvider->getNestedCategories();
        $this->productAdapter = $this->productDataProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
