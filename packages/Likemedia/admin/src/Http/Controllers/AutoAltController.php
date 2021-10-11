<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\AutoAlt;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductImageTranslation;
use App\Models\GalleryImageTranslation;


class AutoAltController extends Controller
{
    public function index()
    {
      return view('admin::admin.autoalts.index');
    }

    public function store(Request $request)
    {
      $this->validate($request, array(
          'keywords' => 'required|mimes:csv,txt',
      ));

      AutoAlt::truncate();

      $handle = fopen($request->file('keywords')->getRealPath(), "r");
      $header = true;
      while ($row = fgetcsv($handle, 0, ",")) {
          if ($header) {
              $header = false;
          } else {
              if(count($row) == 4) {
                  $autoalt = new AutoAlt();
                  $autoalt->cat_id = mb_convert_encoding($row[0], 'utf8', 'cp1251');
                  $autoalt->keywords = mb_convert_encoding($row[3], 'utf8', 'cp1251');
                  $autoalt->lang_id = mb_convert_encoding($row[2], 'utf8', 'cp1251');
                  $autoalt->save();
              } else {
                  return redirect()->back()->withErrors('Insufficient number of columns in the file');
              }
          }
      }

      fclose ($handle);

      $this->parseAutoAlts();

      $gallery_images = GalleryImageTranslation::get();

      foreach ($gallery_images as $key => $gallery_image) {
        $autoAlt = AutoAlt::where('cat_id', 0)->where('lang_id', $gallery_image->lang_id)->pluck('keywords')->toArray();

        if($gallery_image->alt == '') {
          if(count($autoAlt) > 0) {
            if (count($autoAlt) == 1) {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'alt' => $autoAlt[0]
                ]);
            } else {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'alt' => $autoAlt[array_rand($autoAlt)]
                ]);
            }
          }
        }

        if($gallery_image->title == '') {
          if(count($autoAlt) > 0) {
            if (count($autoAlt) == 1) {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'title' => $autoAlt[0]
                ]);
            } else {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'title' => $autoAlt[array_rand($autoAlt)]
                ]);
            }
          }
        }

        if($gallery_image->alt == '' && $gallery_image->title == '') {
          if(count($autoAlt) > 0) {
            if (count($autoAlt) == 1) {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'alt' => $autoAlt[0],
                    'title' => $autoAlt[0],
                ]);
            } else {
                GalleryImageTranslation::where('id', $gallery_image->id)->update([
                    'alt' => $autoAlt[array_rand($autoAlt)],
                    'title' => $autoAlt[array_rand($autoAlt)],
                ]);
            }
          }
        }

      }

      session()->flash('message', 'New autoalt has been created!');

      return redirect()->route('autoalt.index');
    }

    public function parseAutoAlts()
    {
        // get all Categories
        $categoryIDs = ProductCategory::pluck('id');
        if (!empty($categoryIDs)) {
            foreach ($categoryIDs as $key => $categoryID) {
                // check if category has alts
                $autoAlts = AutoAlt::where('cat_id', $categoryID)->pluck('id');
                if (!empty($autoAlts)) {
                    // check if category has products
                    $products = Product::where('category_id', $categoryID)->pluck('id');
                    if(!empty($products)){
                        foreach ($products as $key => $product) {
                            // check if product has images
                            $productImages = ProductImage::select()->where('product_id', $product)->get();
                            if (!empty($productImages)) {
                                foreach ($productImages as $key => $productImage) {
                                    $images = ProductImageTranslation::where('product_image_id', $productImage->id)->get();
                                    if (!empty($images)) {
                                        foreach ($images as $key => $image) {
                                            $autoAlt = AutoAlt::where('cat_id', $categoryID)->where('lang_id', $image->lang_id)->pluck('keywords')->toArray();
                                            if($image->alt == '') {
                                                if(count($autoAlt) > 0) {
                                                    if (count($autoAlt) == 1) {
                                                        ProductImageTranslation::where('id', $image->id)->update([
                                                            'alt' => $autoAlt[0]
                                                        ]);
                                                    } else {
                                                        ProductImageTranslation::where('id', $image->id)->update([
                                                            'alt' => $autoAlt[array_rand($autoAlt)]
                                                        ]);
                                                    }
                                                }
                                            }

                                            if($image->title == '') {
                                              if(count($autoAlt) > 0) {
                                                if (count($autoAlt) == 1) {
                                                    ProductImageTranslation::where('id', $image->id)->update([
                                                        'title' => $autoAlt[0]
                                                    ]);
                                                } else {
                                                    ProductImageTranslation::where('id', $image->id)->update([
                                                        'title' => $autoAlt[array_rand($autoAlt)]
                                                    ]);
                                                }
                                              }
                                            }

                                            if($image->alt == '' && $image->title == '') {
                                              if(count($autoAlt) > 0) {
                                                if (count($autoAlt) == 1) {
                                                    ProductImageTranslation::where('id', $image->id)->update([
                                                        'alt' => $autoAlt[0],
                                                        'title' => $autoAlt[0]
                                                    ]);
                                                } else {
                                                    ProductImageTranslation::where('id', $image->id)->update([
                                                        'alt' => $autoAlt[array_rand($autoAlt)],
                                                        'title' => $autoAlt[array_rand($autoAlt)]
                                                    ]);
                                                }
                                              }
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function exportCategories()
    {
      $categories = ProductCategoryTranslation::all('product_category_id as cat_id', 'name as cat_name', 'lang_id')->toArray();
      $filename = "categories.csv";
      $handle = fopen($filename, 'w+');
      fprintf($handle, "\xEF\xBB\xBF");
      fputcsv($handle, ['cat_id', 'cat_name', 'lang_id']);

      foreach($categories as $category) {
          fputcsv($handle, $category);
      }

      fclose($handle);

      $headers = array(
          'Content-Type' => 'text/csv',
      );

      return response()->download($filename, 'categories.csv', $headers);
    }

}
