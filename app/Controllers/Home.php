<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\SaleHistory;
use App\Models\Vendor;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Database\RawSql;

class Home extends BaseController
{
//    use ResponseTraitnseTrait;

    public function index(): string
    {
        return view('home');
    }

    public function allVendors()
    {
        return $this->response->setJSON((new Vendor())->findAll());
    }

    public function allProducts()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('products');
        $builder->select('*,products.id');
        $builder->join('vendors', 'products.vendor_id = vendors.id');
        $query = $builder->get()->getResult();
        return $this->response->setJSON($query);
    }

    public function buyProducts()
    {
        $productId = $this->request->getVar('id');
        $product = (new Product())->find($productId);
        $vendor = (new Vendor())->find($product['vendor_id']);
        $productAmount = $product['ammount'];

        $commission_percent = $product['commission_percent'];
        $current_year_sale = $vendor['current_year_sale'];

        $commission_discount = $this->commission_discount($current_year_sale);
        $commission_percent -= $commission_percent * ($commission_discount / 100);
        $commison_ammount = $productAmount * $commission_percent / 100;
        $totalAmount = $productAmount - $commison_ammount;

        $db = \Config\Database::connect();
        $data = [
            'product_id' => $productId,
            'vendor_id' => $product['vendor_id'],
            'qty' => 1,
            'orignal_amount' => $productAmount,
            'commison_ammount' => $commison_ammount,
            'total_amount' => $totalAmount,
            'commision_percentage' => $commission_percent,
            'date_of_sale' => new RawSql('CURRENT_TIMESTAMP()'),

        ];
        $builder = $db->table('sale_history');
        $result = $builder->insert($data);
        if ($result) {
            $current_year_sale += $productAmount;
            $commission_discount = $this->commission_discount($current_year_sale);
            $db = \Config\Database::connect();
            $builder = $db->table('vendors');
            $builder->set('current_year_sale', $current_year_sale);
            $builder->set('commision_discount', $commission_discount);
            $builder->where('id', $product['vendor_id']);
            $builder->update();
        }

        return $this->response->setJSON([
            'success' => json_encode($result)
        ]);
    }

    public function saleHistory()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sale_history as s');
        $builder->select('s.id,p.title,p.commission_percent as commission_percent_original,v.name,v.shop_name
        ,s.orignal_amount,s.total_amount,s.commison_ammount,v.commision_discount,
        s.commision_percentage as commission_percent_final,s.date_of_sale,s.order_status
        ');
        $builder->join('vendors as v', 's.vendor_id = v.id');
        $builder->join('products as p', 's.product_id = p.id');
        $builder->orderBy('s.id', 'DESC');
        $query = $builder->get()->getResult();
        return $this->response->setJSON($query);
    }

    public function returnProduct()
    {
        $saleHistoryId = $this->request->getVar('id');
        $saleHistoryDetail = (new SaleHistory())->find($saleHistoryId);
        $db = \Config\Database::connect();
        $data = [
            'sale_history_id' => $saleHistoryId,
            'refund_ammount' => $saleHistoryDetail['orignal_amount'],
            'refund_date' => new RawSql('CURRENT_TIMESTAMP()'),
        ];
        $builder = $db->table('return_history');
        $builder->insert($data);


        $vendor = (new Vendor())->find($saleHistoryDetail['vendor_id']);
        $current_year_sale = $vendor['current_year_sale'];
        $current_year_sale -= $saleHistoryDetail['orignal_amount'];
        if ($current_year_sale < 0) {
            $current_year_sale = 0;
        }
        $commission_discount = $this->commission_discount($current_year_sale);
        $db = \Config\Database::connect();
        $builder = $db->table('vendors');
        $builder->set('current_year_sale', $current_year_sale);
        $builder->set('commision_discount', $commission_discount);
        $builder->where('id', $saleHistoryDetail['vendor_id']);
        $builder->update();


        $db = \Config\Database::connect();
        $builder = $db->table('sale_history');
        $builder->set('order_status', 'Returned');
        $builder->where('id', $saleHistoryId);
        $result = $builder->update();


        return $this->response->setJSON($result);

    }

    public function reset()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sale_history');
        $builder->truncate();
        $db = \Config\Database::connect();
        $builder = $db->table('return_history');
        $builder->truncate();
        $db = \Config\Database::connect();
        $builder = $db->table('vendors');
        $builder->set('current_year_sale', 0);
        $builder->set('commision_discount', 0);
        $result = $builder->update();
        return $this->response->setJSON($result);


    }

    protected function commission_discount($current_year_sale)
    {
        if ($current_year_sale > 1000) {
            $commission_discount = 100;
        } elseif ($current_year_sale > 500) {
            $commission_discount = 20;
        } elseif ($current_year_sale > 100) {
            $commission_discount = 10;
        } else {
            $commission_discount = 0;
        }
        return $commission_discount;

    }


}
