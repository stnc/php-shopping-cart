<?php 
namespace Stnc\ShoppingCart;

use \Stnc\ShoppingCart;
//use \Core\View, \Lib\Paginator, \Core\Controller as controller; smell code 
class HtmlHelper extends Cart
{

    public function hello(){
        echo "merhaba";
    }
    /*
     * sepetteki ler hakkında bilgi verir
     * ajax sepete ekle ye gibi minik olan alana dışarı bilgi gondermeye yarar
     * en üst alandaki header view içindeki bilgilerin görüneceği alandır
     * @param $type json a cıktı gonderilecek mi
     * @return mixed
     */
    public function miniCart()
    {
        if (is_array($this->session) && sizeof($this->session) > 0) {
            $products = '<div class="mini-cart-info"><table>	<tbody>	';

  

            foreach ($this->session as $id => $item) {
                // $this->session [$id] ['totalPrice'] = ($this->session [$id] ['price'] * $this->session [$id] ['totalEach']);
                $products .= '<tr id="mini-cart' . $id . '"><td class="image"><a href="' . $item['URL'] . '">
				<img title="' . $item['URL'] . '" alt="' . $item['productID'] . '" width="43" height="43" src="' . $item['productImageURL'] . '"></a></td>
                <td class="name">
                <a href="' . $item['URL'] . '">
				<div style="width: 115px; height: 60px; overflow: hidden;">"' . $item['productID'] . '"</div>
				</a></td>
				<td class="quantity" style="width: 90px;">
				<span class="price2">' . $item['price'] . 'x</span>' . $item['totalEach'] . 'KG
				</td>
				<td class="total" style="width: 90px;">' .$item['totalPrice'] . ' TL</td>
				<td class="remove"><a  onclick="sepeti_sil(' . $id . ',true );" href="javascript: void(0)"  class="sil">
			    <img title="Remove" alt="Remove" src="' . $this->publicPath . '/public/img/remove.png"></a></td></tr>';
            }
            $products .= "
		</tbody>
		</table>
         </div>";

            $products .= '<div class="mini-cart-total">' . $this->viewCartTablePrice() . '</div>
            <div class="checkout">
			<a href="javascript: void(0)" class="button sepetikapat"><i class="fa fa-times" aria-hidden="true"></i> Kapat</a> &nbsp;
			<a href="/sepetim/" class="button">Sepetim</a> &nbsp;
			<a class="button" href="/adres">Ödeme Yap</a>
			</div>';

            return $products;
        } // direk olarak sepet boş uyarısı vermesi içindir
        else {
            return '<div class="mini-cart-info"><h3 style="text-align:center;color:#000">Alışveriş Sepetiniz Boş</h1></div>
                <div class="mini-cart-total"></div>
				<div style="display:none" class="checkout">
					<a href="javascript: void(0)" class="button sepetikapat"><i class="fa fa-times" aria-hidden="true"></i> Kapat</a> &nbsp;
				<a href="/sepetim/" class="button">Sepetim</a> &nbsp;
			<a class="button" href="/adres">Ödeme Yap</a>
			</div>';
        }
    }






     /*
     * sepet sayfası na basılıcak yerdir
     * sepetteki ler hakkında bilgi verir
     * $liste nin anlamı sadece cart ın listelenmesinin isteyen istekler olabilir mesela odeme
     * içinden gelen bu $sepet->viewCartTableFull('liste'); istek gibi
     * @return mixed
     */
    public function viewCartTableFull()
    {
        if (is_array($this->session) && sizeof($this->session) > 0) {

            $class = 'class="table table-striped" style="border:1px solid #ddd"';

            $products = '
        <div class="cart-info">
       	<table ' . $class . '>
            <thead>
              <tr>
                <td class="image">İmage</td>
                <td class="name">Content</td>

                <td class="quantity">Each</td>
                 <td class="unit">unit</td>
                <td class="price"> Price</td>
                <td class="total">total</td>
              </tr>
            </thead>
            <tbody class="sepetsatirlari">';

            foreach ($parent::session as $id => $item) {
                // $this->session [$id] ['totalPrice'] = ($this->session [$id] ['price'] * $this->session [$id] ['totalEach']);

                // sil diğer kodları <a onclick="sepeti_sil_sepetim(' . $id . ',true );" href="javascript: void(0)" class="sil">

                if ($item['productImageURL'] == '') {
                    $resim = noPicture;
                } else {
                    $resim = $item['productImageURL'];
                }

                $input = '';
                $item['amountOfStock'] = '';
                if ($item['stockUnit'] == 'unit') {

                    $input = '<div class="input-group spinner table-spinner" data-trigger="spinner">
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['totalEach'] . '"
                        id="urun_adeti_' . $item['productID'] . '" data-min="1" data-max="' . $item['amountOfStock'] . '" data-rule="quantity">
                        <div class="input-group-addon">
                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                            </div>
                        </div>
                        ';
                }
                //Kilogram //KİLOGRAM
                if ($item['stockUnit'] == 'KG') {

                    $input = '<div class="input-group spinner table-spinner" data-trigger="spinner">
    <input type="text" class="form-control quantity text-center urun_adeti" value="' . $item['totalEach'] . '"
                        id="urun_adeti_' . $item['productID'] . '" data-step="0.10" data-min="0.10" data-max="' . $item['amountOfStock'] . '" data-rule="currency">
                        <div class="input-group-addon">
                                <a href="javascript:;" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                                <a href="javascript:;" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                            </div>
                        </div>
                        ';

                }

                $deger = '

                    <div class="input-group">
                         ' . $input . '
					</div>

                            <a href="?clear=' . $id . '"  class="sil"> 
			   						 <img title="Remove" alt="Remove" src="/public/img/remove.png" onclick="var r = confirm(\'Emin misiniz?\'); if (r == true) return true; else return false;">';

                $products .= '<tr id="full-cart' . $id . '" class="sepetsatiri">
                <td class="image">
				<a href="' . $item['URL'] . '">

                <img src="' . $resim . '" style="height:35px;" alt="' . $item['productID'] . '" title="' . $item['productID'] . '"></a>

                </td>
                <td class="name">
                <a href="' . $item['URL'] . '">' . $item['productName'] . '</a>
                </td>

                <td class="quantity">
            			' . $deger . '
			    </td>
            			             <td class="unit">
            			' . ($item['stockUnit']) . '
			    </td>
			    <td class="price">' . ($item['price']) . ' $</td>
                <td id="total_' . $item['productID'] . '" class="total">' . ($item['totalPrice']) . ' TL</td>
              </tr>';
            }
            $products .= ' </tbody>
          </table>
        </div>
   ';
            return $products;
        } else {
            return '<h1>Your shopping cart is empty</h1><br>
					<a href="/">Click here to continue shopping.</a>
					';
        }
    }



    
    /*
     * sepetteki ler hakkında bilgi verir
     * @return mixed
     */
    public function viewCartTablePrice()
    {
        $products = "<table>
		<tbody>
		";
        if (is_array($this->session) && sizeof($this->session) > 0) {

            $tot = $this->cartCount();
            $products .= '<tr>
							<td class="right"><b>Toplam Ürün:</b></td>
							<td class="right">' . $tot["totalProduct"] . ' Ürün</td>
							</tr>
							<tr>
							<td class="right"><b>Toplam Adet:</b></td>
							<td class="right">' . $tot["totalPiece"] . ' Adet</td>
							</tr>
							<tr class="price2">
							<td class="right"><b>Toplam Tutar:</b></td>
							<td class="right">' . ($parent::subTotal) . ' TL</td>
							<tr>';
        } else { // sepet boş ise
            $products .= '
							<tr>
							<td colspan="2" ><h3 style="text-align:center">Sepetinizde ürün bulunmamaktadır</h3></td>
							</tr>
';
        }
        $products .= "
		</tbody>
		</table>";

        return $products;
    }
  /*
     * sepeti json olarak verir
     * @return array json doner
     */
    public function getJSONV1()
    {
        if (!$_SESSION[$this->productWareHouseControl]) {
            if (is_array($this->session) && sizeof($this->session) <= 0) {
                $tot = $this->cartCount();
                $json = array(
                    "DURUM" => 'ok',
                    // "addLastCartStockPiece" => $this->addLastCartStockPiece(),/*burasının amacı sepetim sayfasında degisken_max_adetine deger vermesi içindir */
                    "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                    "getLastCartPriceInfo" => $parent::getLastCartPriceInfo(),
                    "getLastCartPiece" => $parent::getLastCartPiece(),
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => $tot["totalPiece"] . ' Adet <strong class="price2">' . $parent::subTotal . ' TL</strong>',
                    "SepettotalPrice" => $this->updatesubTotal() . ' TL',
                );
                return json_encode($json);
            } else {
                $json = array(
                    "DURUM" => 'bos',
                    "SepetSatirlari" => null,
                    "getLastCartPriceInfo" => null,
                    "getLastCartPiece" => null,
                    "SepetToplamKodu" => $this->viewCartTablePrice(),
                    "SepetUst" => "",
                    'SepetLimit' => true,
                    "SepettotalPrice" => "",
                );
                return json_encode($json);
            }
        } else {

            $json = array(
                "DURUM" => 'stok_asimi',
                "SepetSatirlari" => $this->viewCartTableMiniJSON(),
                "getLastCartPiece" => $parent::getLastCartPiece(),
                "getLastCartPriceInfo" => $parent::getLastCartPriceInfo(),
                "SepetToplamKodu" => $this->viewCartTablePrice(),
                'SepetLimit' => true,
                "SepetUst" => $tot["totalPiece"] . ' Adet <strong class="price2">' . $parent::subTotal . ' TL</strong>',
                "SepettotalPrice" => $parent::subTotal . ' TL',
            );
            return json_encode($json);
        }
    }


    }



}