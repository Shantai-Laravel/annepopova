<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PagesController as PageItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\SubProduct;
use App\Models\Cart;

class WishListController extends Controller
{
  /**
   *  get action
   *  Render wish page
   */
    public function index() {
        $page = Page::where('alias', 'favorites')->first();

        if (is_null($page)) {
            abort(404);
        }

        $userdata = $this->checkIfLogged();

        $data = $this->getWishItems();

        $pageItem = new PageItem;
        $seoData = $pageItem->getSeo($page);

        return view('front.'. $this->device .'.dynamic.wish', compact('data', 'seoData'));
    }
    /**
     *  get action (vue js)
     *  Return wishlist products
     */
    public function getWishItems()
    {
        $userdata = $this->checkIfLogged();

        $data['products'] = WishList::with([
                                        'subproduct.parameterValue.translation',
                                        'product.subproducts.parameterValue.translation',
                                        'product.translation',
                                        'product.mainImage',
                                        'product.mainPrice',
                                        'product.personalPrice',
                                        'product.category',
                                        'product.subproducts.warehouse',
                                        'product.warehouse',
                                    ])
                                    ->where('user_id', $userdata['user_id'])
                                    ->get();

        $data['sets'] = WishListSet::with([
                                        'set.translation',
                                        'set.mainPhoto',
                                        'set.personalPrice',
                                        'set.products.category',
                                        'set.products.translation',
                                        'set.products.personalPrice',
                                        'set.products.mainImage',
                                        'set.products.setImages',
                                        'set.products.subproducts.parameterValue.translation',
                                        'set.products.subproducts.parameter',
                                    ])
                                    ->where('user_id', $userdata['user_id'])
                                    ->get();

        return $data;
    }
    /**
     *  post action (vue js)
     *  Add product to wishlist and return added products
     */
    public function addToFavorites(Request $request)
    {
        $delete = 'false';
        $userdata = $this->checkIfLogged();

        $product = Product::find($request->get('product_id'));

        if (!is_null($product)) {
            $wishlist = WishList::where('user_id', $userdata['user_id'])
                        ->where('product_id', $product->id)
                        ->first();

            if (is_null($wishlist)) {
                WishList::create([
                    'product_id'    => $product->id,
                    'subproduct_id' => null,
                    'user_id'       => $userdata['user_id'],
                    'is_logged'     => $userdata['is_logged']
                ]);
            }
        }

        $data['products'] = $this->getWishItems();
        $data['status'] = $delete;
        return $data;
    }
    /**
     *  post action (vue js)
     *  Remove product from wishlist and return deleted product
     */
    public function removeProductWish(Request $request) {
        $userdata = $this->checkIfLogged();

        $wishListProduct = WishList::where('user_id', $userdata['user_id'])
                                ->where('id', $request->get('id'))
                                ->first();

        WishList::where('id', $wishListProduct->id)->delete();

        return $this->getWishItems();
    }
    /**
     *  post action (vue js)
     *  Remove product from wishlist and return deleted product
     */
    public function removeSetWish(Request $request) {
        $userdata = $this->checkIfLogged();

        $wishListSet = WishListSet::where('user_id', $userdata['user_id'])
                                ->where('id', $request->get('id'))
                                ->first();

        WishListSet::where('id', $wishListSet->id)->delete();

        return $this->getWishItems();
    }
    /**
     *  post action (vue js)
     *  Move product from wishlist to cart and return deleted product
     */
    public function moveProductToCart(Request $request)
    {
        $userdata = $this->checkIfLogged();

        $wishListProduct = WishList::where('user_id', $userdata['user_id'])->where('id', $request->get('id'))->first();

        if (!is_null($wishListProduct)) {
            $subproduct = SubProduct::find($request->get('subproductId'));

            if(!is_null($subproduct)) {
              Cart::create([
                  'product_id' => $wishListProduct->product_id,
                  'subproduct_id' => $subproduct->id,
                  'user_id' => $userdata['user_id'],
                  'qty' => 1,
                  'is_logged' => $userdata['is_logged']
              ]);
            }else{
              Cart::create([
                  'product_id' => $wishListProduct->product_id,
                  'subproduct_id' => 0,
                  'user_id' => $userdata['user_id'],
                  'qty' => 1,
                  'is_logged' => $userdata['is_logged']
              ]);
            }

            $wishListProduct->delete();
        } else {
            return response()->json('Something failed', 400);
        }

        $data['wishListProduct'] = $wishListProduct;
        $data['cart'] = Cart::where('user_id', $userdata['user_id'])->get();
        return $data;
    }

    public function addSetToWish(Request $request)
    {
        $userdata = $this->checkIfLogged();

        $wishSet = WishListSet::where('set_id', $request->get('setId'))->where('user_id', $userdata['user_id'])->first();

        if (is_null($wishSet)) {
            WishListSet::create([
                'set_id' => $request->get('setId'),
                'user_id' => $userdata['user_id'],
            ]);
        }

        return $this->getWishItems();
    }


    /**
     *  private method
     *  Check if user is logged
     */
    private function checkIfLogged() {
        if(auth('persons')->guest()) {
            return array('is_logged' => 0, 'user_id' => $_COOKIE['user_id']);
        } else {
            return array('is_logged' => 1, 'user_id' => auth('persons')->id());
        }
    }
}
