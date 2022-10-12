<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(ManagerRegistry $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute("app_account_address_add");
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }
     #[Route('/order/recap', name: 'app_order_recap')]
        public function add(Cart $cart, Request $request): Response
        {
            $doctrine =  $this->entityManager->getManager();
            if(!$this->getUser()->getAddresses()->getValues()){
                return $this->redirectToRoute("app_account_address_add");
            }
            $form = $this->createForm(OrderType::class, null, [
                'user' => $this->getUser()
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $date = new \DateTimeImmutable();
                $carriers = $form->get('carriers')->getData();
                $delivery = $form->get('addresses')->getData();
                $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();

                if ($delivery->getCompany()){
                    $delivery_content .= '<br/>'. $delivery->getCompany();
                }

                $delivery_content .= '<br/>'. $delivery->getAdresse();
                $delivery_content .= '<br/>'. $delivery->getPostal().' '.$delivery->getCity();
                $delivery_content .= '<br/>'. $delivery->getCountry();
                // save order -> order
                $order = new Order();
                $order->setUser($this->getUser());
                $order->setCreatedAt($date);
                $order->setCarrierName($carriers->getName());
                $order->setCarrierPrice($carriers->getPrice());
                $order->setDelivery($delivery_content);
                $order->setIsPaid(0);
                $doctrine->persist($order);

                foreach ($cart->getFull() as $product){
                    $orderDetails = new OrderDetails();
                    $orderDetails->setMyOrder($order);
                    $orderDetails->setProduct($product['product']->getName());
                    $orderDetails->setQuantity($product['quantity']);
                    $orderDetails->setPrice($product['product']->getPrice());
                    $orderDetails->setTotal($product['product']->getPrice()*$product['quantity']);
                    $doctrine->persist($orderDetails);
                }
                $doctrine->flush();

            }
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull()
            ]);
        }
}
