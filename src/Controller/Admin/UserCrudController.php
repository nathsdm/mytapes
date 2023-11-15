<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Id shouldn't be modified
            IdField::new('id')->hideOnForm(),
            
            // Other fields
            AssociationField::new('member')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/user_member.html.twig'),
            
            TextField::new('email'),

            TextField::new('password')
                ->onlyOnForms()
                ->setFormTypeOption('empty_data', '')
                ->setFormTypeOption('required', false),
            
        ];
    }
    
    public function configureActions(Actions $actions): Actions
    {
        // For whatever reason show isn't in the menu, by default
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->overrideTemplate('crud/index', 'admin/crud/user_index.html.twig')
        ;
    }
}


