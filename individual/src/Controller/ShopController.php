<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Entity\ShopProduct;
use App\Form\ShopProductType;
use App\Form\ShopType;
use App\Form\SearchShopType;
use App\Repository\ShopRepository;
use App\Repository\ShopProductRepository;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop/{id<\d+>}', name: 'app_shop_single', methods: ["GET"])]
    public function getShop(int $id, ShopRepository $shopRepository): Response
    {
        $shop = $shopRepository->find($id);

        if (!$shop) {
            throw new NotFoundHttpException("Product with:{$id} is not found");
        }

        return $this->render('shop/index.html.twig', [
            'shop' => $shop,
        ]);
    }

    #[Route('/shop', name: 'app_shop_list', methods: ["GET"])]
    public function getShopList(ShopRepository $shopRepository): Response
    {
       $shop = $shopRepository->findAll();
        return $this->render('shop/list.html.twig', [
            'shops' => $shop,
        ]);
    }

    #[Route('/shop/remove/{id<\d+>}', name: 'app_shop_remove')]
    public function removeShop(int $id, ShopRepository $shopRepository): Response
    {
        $shop = $shopRepository->find($id);

        if (!$shop) {
            $this->addFlash('error', "Product with id: {$id}  is not found!");
            return $this->redirectToRoute('app_shop_list');
        }

        $shopRepository->remove($shop, true);
        $this->addFlash('success', "Product with id: {$id} is found");

        return $this->redirectToRoute('app_shop_list');
    }

    #[Route('/shop/update/{id<\d+>}', name: 'app_shop_update', methods: ['GET', 'POST'])]
    public function updateShop(int $id, Request $request, ShopRepository $shopRepository, ShopProductRepository $shopProductRepository): Response
    {
        $shop = $shopRepository->find($id);

        if(!$shop) {
            $this->addFlash('error', "Product with id {$id} not found!");
            return $this->redirectToRoute('app_shop_list');
        }

        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shop = $form->getData();
            $shopRepository->save($shop, true);
            foreach ($shop->getShopProducts() as $pShop) {
                $shopProduct = new ShopProduct();
                $shopProduct->setShop($shop);
                $shopProduct->setProduct($pShop->getProduct());
                $shopProduct->setStatus('');
                $shopProductRepository->save($shopProduct,true);
            }
            $this->addFlash('success', "Product with id:{$id} is saved");
            return $this->redirectToRoute('app_shop_update', ['id' => $id]);
        }

        return $this->renderForm('shop/form.html.twig', [
            'form' => $form,
            'shop' => $shop
        ]);
    }

    #[Route('/shop/add', name: 'app_shop_add', methods: ['GET', 'POST'])]
    public function addShop(Request $request, ShopRepository $shopRepository): Response
    {
        $shop = new Shop();

        $form = $this->createForm(ShopType::class, $shop);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shop = $form->getData();
            $shopRepository->save($shop, true);
            $this->addFlash('success', "Product with id:{$shop->getId()} is created!");
            return $this->redirectToRoute('app_shop_list');
        }

        return $this->renderForm('shop/form.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/shop/search', name: 'app_shop_search', methods: ['GET', 'POST'])]
    public function searchShop(Request $request, ShopRepository $shopRepository, LoggerInterface $logger): Response
    {
        $search = [
            'name' => $request->query->get('name'),
            'price' => $request->query->get('price') ? $request->query->get('price') : '0',
            'order' => $request->query->get('order') ? $request->query->get('order')  : 'max',
        ];

        if($search['order'] != 'max' && $search['order'] != 'min') {
            return new Response('Error! Price query must me `max` or `min`'.$search['order']);
        }

        $shop = new Shop();


        $form = $this->createForm(SearchShopType::class, $shop);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $price = $form->get('price')->getData();
            $order = $form->get('order')->getData();
            return $this->redirect($this->generateUrl('app_shop_search', array('name' => $name, 'price' => $price, 'order' => $order)));
        }

        $shop = $shopRepository->findByCriteria($search['name'], $search['price'], $search['order']);

        $logger->alert(serialize($shop));

        return $this->renderForm('shop/search.html.twig', [
            'shops' => $shop,
            'form' => $form
        ]);
    }
}
