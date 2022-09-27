<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {

        $this->session = $requestStack->getSession();
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart',$cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }
    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }
    public function more($id)
    {
        $cart = $this->session->get('cart', []);
        $cart[$id]++;
        return $this->session->set('cart', $cart);
    }
    public function less($id)
    {
        $cart = $this->session->get('cart', []);
        if ($cart[$id]>1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }
}