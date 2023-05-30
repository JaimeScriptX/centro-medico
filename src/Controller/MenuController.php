<?php
// src/Controller/MenuController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MenuRepository;

class MenuController extends AbstractController
{
	private $menuRepository;
	
	public function __construct ( MenuRepository $menuRepository )
    {
			$this->menuRepository = $menuRepository;
	}
	/**
     * @Route("/menu_menu",  name="menu_menu")
     */
	public function menu(): Response
    {
		$menus = $this->menuRepository->findMenu();

        return $this->render('menu/menu.html.twig',array("menus"=>$menus));
    }   
}
