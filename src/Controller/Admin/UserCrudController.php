<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = [ 'ROLE_ADMIN', 'ROLE_BOTANIST', 'ROLE_USER' ];
        return [
            
            yield EmailField::new('email'),
            yield TextField::new('pseudo'),
            yield TextField::new('telephone'),
            // yield TextField::new('password')
            //     ->setFormType(PasswordType::class)
            //     ->hideOnIndex()
            // ,
            // yield ArrayField::new('roles')
            //     ->setHelp('Available roles: ROLE_BOTANIST, ROLE_ADMIN, ROLE_USER')
            // ,
            
            yield ChoiceField::new( 'roles' )
            ->setChoices( array_combine( $roles, $roles ) )
            ->allowMultipleChoices()
            ->renderAsBadges()
            ,
        ];
    }
}
