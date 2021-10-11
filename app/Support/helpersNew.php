<?php

function getPage($alias, $lang)
{
    $page = DB::table('pages')
        ->join('pages_translation', 'pages_translation.page_id', '=' ,'pages.id')
        ->where('lang_id', $lang)
        ->where('alias', $alias)
        ->first();

    return $page;
}

function chechSubproductVals($filter, $currentVal, $productId, $itemId){
    $flag = false;
    $vals = [];
    if ($filter) {
        foreach ($filter as $key => $value) {
            $vals[] = $value['valueId'];
        }
    }

    $inactive = DB::table('subproducts')
        ->select('combination')
        ->where('active', 0)
        ->where('product_id', $productId)
        ->get();

        if (count($inactive) > 0) {
            foreach ($inactive as $key => $inactiv) {
                if (!in_array($itemId, $vals)) {
                $comb = (array) json_decode($inactiv->combination);
                    if (diffArray($vals, $comb) && in_array($itemId, $comb)) {
                        return true;
                    }
                }
            }
        }

        return false;
}

function chechSubproduct($productId, $itemId)
{
    $inactive = DB::table('subproducts')
        ->select('combination')
        ->where('active', 0)
        ->orWhere('stock', 0)
        ->where('product_id', $productId)
        ->get();

        if (count($inactive) > 0) {
            foreach ($inactive as $key => $inactiv) {
                // if (!in_array($itemId, $vals)) {
                $comb = (array) json_decode($inactiv->combination);
                    if (in_array($itemId, $comb)) {
                        return true;
                    }
                // }
            }
        }

        return false;
}

function diffArray($vals, $combs)
{
    $rezult = [];
    foreach ($combs as $key => $comb) {
        if (in_array($comb, $vals)) {
            if ($comb !== 0) {
                $rezult[] = $comb;
            }
        }
    }
    if (count($rezult) > 0) {
        return true;
    }
    return false;
}

function checkValue($currentValue, $value, $productId)
{
    if ($currentValue == null) {
        return false;
    }
    $inactive = DB::table('subproducts')
        ->where('active', 0)
        ->where('product_id', $productId)
        ->pluck('combination_id')
        ->toArray();

        if (count($inactive) > 0) {
            foreach ($inactive as $key => $inactiv) {
                $configuration = DB::table('subproduct_combinations')
                    ->where('id', $inactiv)
                    ->first();

                    if (!is_null($configuration)) {
                        $valuesList = $configuration->case_1 + $configuration->case_2 + $configuration->case_3;
                        $existsList = $currentValue + $value;
                        if ($valuesList == $existsList) {
                            return true;
                        }
                    }
            }
        }
        return false;
}
/**
 * @param $category_id
 * @param $lang_id
 * @return boolean
 */
function checkAutometasCategoryEdit($category_id, $lang_id, $type, $meta_id)
{
  $checked = DB::table('autometa_categories')
      ->join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
      ->where('lang_id', $lang_id)
      ->where('type', $type)
      ->where('category_id', $category_id)
      ->where('autometa_id', $meta_id)
      ->get();
  if(count($checked) > 0) {
    return true;
  }

  return false;
}

/**
 * @return int
 */
function genMetaId()
{
  $meta_id = rand(1, 1000);
  $temp_id = DB::table('autometas')->where('meta_id', $meta_id)->get();
  if(count($temp_id) > 0) {
    genMetaId();
  } else {
    return $meta_id;
  }
}

function checkAutometasCategoryCreate($category_id, $lang_id, $type){
    $row = DB::table('autometa_categories')
        ->join('autometas', 'autometa_categories.autometa_id', 'autometas.meta_id')
        ->where('lang_id', $lang_id)
        ->where('type', $type)
        ->where('category_id', $category_id)
        ->first();

    if (!is_null($row)) {
        return true;
    }
    return false;
}

function GetGallery($shot_code, $langId)
{
    $gallery = DB::table('galleries')
        ->select('id')
        ->where('alias', $shot_code)
        ->first();

    if (!is_null($gallery)) {

        $table = "gallery_images";

        $row = DB::table($table)
            ->join('gallery_images_translation', 'gallery_images_translation.gallery_image_id', '=', $table . '.id')
            ->where('lang_id', $langId)
            ->where('gallery_id', $gallery->id)
            ->limit(4)
            ->get();

            return $row;
    }

        return false;
}

function GetGalleryById($id, $langId)
{
    $gallery = DB::table('galleries')
        ->select('id')
        ->where('id', $id)
        ->first();

    if (!is_null($gallery)) {

        $table = "gallery_images";

        $row = DB::table($table)
            ->join('gallery_images_translation', 'gallery_images_translation.gallery_image_id', '=', $table . '.id')
            ->where('lang_id', $langId)
            ->where('gallery_id', $gallery->id)
            ->limit(4)
            ->get();

            return $row;
    }

        return false;
}



function getParamProdValue($parameterId, $productId)
{
    $table = "parameters_values_products";

    $row = DB::table($table)
        ->select('parameter_value_id')
        ->where('product_id', $productId)
        ->where('parameter_id', $parameterId)
        ->first();

    if (!is_null($row)) {
        return $row->parameter_value_id;
    }

    return null;
}

function getParameterChechedItems($parameterId, $productId)
{
    $table = "parameters_values_products";

    $row = DB::table($table)
        ->select('parameter_value_id')
        ->where('product_id', $productId)
        ->where('parameter_id', $parameterId)
        ->get()
        ->pluck('parameter_value_id')->toArray();

    return $row;
}


function getCategories($parent_id, $lang_id) {
  $row = DB::table('product_categories')
      ->join('product_categories_translation', 'product_categories_translation.product_category_id', '=', 'product_categories.id')
      ->where('parent_id', $parent_id)
      ->where('lang_id', $lang_id)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}


function getProductImages($product_id, $lang_id) {
  $row = DB::table('product_images')
      ->join('product_images_translation', 'product_images.id', '=', 'product_images_translation.product_image_id')
      ->where('lang_id', $lang_id)
      ->where('product_id', $product_id)
      ->get();
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getPromotionProducts($lang_id) {
  $row = DB::table('products')
      ->join('products_translation', 'products.id', '=', 'products_translation.product_id')
      ->where('lang_id', $lang_id)
      ->where('promotion_id', '!=', 0)
      ->orderBy('products.created_at', 'desc')
      ->select('products.*', 'products.alias as productAlias','products_translation.*')
      ->paginate(12);
    if (!empty($row)) {
        return $row;
    }
    return false;
}

function getRecomendedProducts($lang_id) {
  $row = DB::table('products')
      ->join('products_translation', 'products.id', '=', 'products_translation.product_id')
      ->where('lang_id', $lang_id)
      ->where('recomended', 1)
      ->orderBy('products.created_at', 'desc')
      ->limit(15)
      ->select('products.*', 'products.alias as productAlias','products_translation.*')
      ->get();

     if (!empty($row)) {
        return $row;
    }
    return false;
}

// function getPromosImage($lang_id) {
//   $row = DB::table('promotions')
//       ->join('promotions_translation', 'promotions.id', '=', 'promotions_translation.promotion_id')
//       ->where('lang_id', $lang_id)
//       ->orderBy('promotions.created_at', 'desc')
//       ->limit(5)
//       ->get();
//     if (!empty($row)) {
//         return $row;
//     }
//     return false;
// }

// function getBrandsImage($lang_id) {
//   $row = DB::table('brands')
//       ->join('brands_translation', 'brands.id', '=', 'brands_translation.brand_id')
//       ->where('lang_id', $lang_id)
//       ->orderBy('brands.created_at', 'desc')
//       ->get();
//     if (!empty($row)) {
//         return $row;
//     }
//     return false;
// }

// function getCategoryName($alias, $lang_id) {
//   $category = DB::table('product_categories')
//       ->join('product_categories_translation', 'product_categories.id', '=', 'product_categories_translation.product_category_id')
//       ->where('lang_id', $lang_id)
//       ->where('alias', $alias)
//       ->first();
//     if (count($category) > 0) {
//         return $category->name;
//     }
//     return false;
// }

// function getParentCategory($category_id, $lang_id) {
//     $categoryArr = [];
//
//     if(count(hasParent($category_id, $lang_id)) > 0) {
//       $temp = hasParent($category_id);
//       array_push($categoryArr, $temp->alias);
//
//       if(count(hasParent($temp->parent_id, $lang_id)) > 0) {
//         $temp = hasParent($temp->parent_id);
//         array_push($categoryArr, $temp->alias);
//
//         if(count(hasParent($temp->parent_id, $lang_id)) > 0) {
//           $temp = hasParent($temp->parent_id);
//           array_push($categoryArr, $temp->alias);
//         }
//       }
//     }
//     return implode('/', array_reverse($categoryArr));
// }
//
// function hasParent($category_id) {
//     $hasParent = DB::table('product_categories')
//           ->where('id', $category_id)
//           ->first();
//     return $hasParent;
// }

function getContactInfo($title) {
    $contactModel = new App\Models\Contact();
    $row = $contactModel->where('title', $title)->first();
    return $row;
}

function YoutubeID($url) {
    if(strlen($url) > 11)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
        {
            return $match[1];
        }
        else
            return false;
    }

    return $url;
}

function pathWithoutLang($path, $langs)
{
    $pathWithBar = '|'.$path;

    if (!empty($langs)) {
        foreach ($langs as $key => $lang) {
            if (strpos($pathWithBar, '|'.$lang->lang) !== false) {
                return substr($path, 3);
            } else {
                continue;
            }
        }
    }
}

function getProducts()
{
    $productModel = new App\Models\Product();
    $row = $productModel::all();
    return $row;
}

function checkProductsSimilar($product_id, $category_id) {
  $row = DB::table('similar_products')
        ->where('product_id', $product_id)
        ->where('category_id', $category_id)
        ->first();

  if(count($row) > 0) {
    return true;
  }
  return false;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getProductLink($categoryId)
{
  $subcat = DB::table('product_categories')
      ->select('alias', 'parent_id')
      ->where('id', $categoryId)
      ->first();

  if(!is_null($subcat)) {
      $cat = DB::table('product_categories')
          ->select('alias')
          ->where('id', $subcat->parent_id)
          ->first();

      if(!is_null($cat)) {
         return $cat->alias.'/'.$subcat->alias.'/';
      }else{
          return $subcat->alias.'/';
      }
  }

  return false;
}

function getSubcats($categoryId, $langId)
{
    $table = "product_categories";

    $row = DB::table($table)
        ->join('product_categories_translation', 'product_categories_translation.product_category_id', '=', $table . '.id')
        ->where('lang_id', $langId)
        ->where('parent_id', $categoryId)
        ->get();

    if (!is_null($row)) {
        return $row;
    }

    return false;
}

function getParamCategory($param, $categ)
{
    $table = "subproduct_properties";

    $row = DB::table($table)
        ->where('product_category_id', $categ)
        ->where('property_id', $param)
        ->first();

    if (!is_null($row)) {
        return $row;
    }

    return null;
}


// function getLangById($langId) {
//     $table = "langs";
//
//     $row = DB::table($table)
//         ->where('id', $langId)
//         ->first();
//
//     if (!is_null($row)) {
//         return $row;
//     }
//
//     return null;
// }



// function SelectCollectionsTree($lang_id)
// {
//     $collections = DB::table('collections_translation')
//         ->join('collections', 'collections_translation.collection_id', '=', 'collections.id')
//         ->where('lang_id', $lang_id)
//         ->orderBy('position', 'asc')
//         ->get();
//
//     return $collections ?? null;
// }

?>
