<?php
namespace EMS\LocalUserBundle\Form;

use EMS\CoreBundle\EMSCoreBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class UserProfileType extends AbstractType {
	
	/**@var TokenStorageInterface */
	private $tokenStorage;
	
	public function __construct(TokenStorageInterface $tokenStorage) {
		$this->tokenStorage = $tokenStorage;
	}
	
	
	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder
			->add ('displayName')
			->add ('emailNotification', CheckboxType::class, [
					'required' => false,
			])
			->add ('layoutBoxed')
			->add ('sidebarMini')
			->add ('sidebarCollapse')
			->remove('username');
		
// 		if($this->tokenStorage->getToken()->getUser()->getAllowedToConfigureWysiwyg()){
			$builder
				->add('wysiwygProfile', EntityType::class, [
					'required' => false,
					'label' => 'WYSIWYG profile',
					'class' => 'EMSCoreBundle:WysiwygProfile',
					'choice_label' => 'name',
					'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('p')->orderBy('p.orderKey', 'ASC');
					},
				])
				->add('wysiwygOptions', TextareaType::class, [
						'required' => false,
						'label' => 'WYSIWYG custom options',
						'attr' => [
							'rows' => 8,
						]
				]);			
// 		}
	}



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /*set the default option value for this kind of compound field*/
        parent::configureOptions($resolver);
        $resolver->setDefault('translation_domain', EMSCoreBundle::TRANS_DOMAIN);
    }
	
	public function getParent()
	{
		return 'FOS\UserBundle\Form\Type\ProfileFormType';
	}
	
}
