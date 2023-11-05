<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <!-- STYLES -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
<div class="container" x-data="multiCart()">
    <form action="post" @submit.prevent="reset()">
        <button>Reset Sale History and return History</button>
    </form>
    <div class="row">
        <div class="col">
            <h1>Vendors</h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Current Year Sale</th>
                    <th scope="col">Commision Discount</th>
                </tr>
                </thead>
                <tbody>
                <template x-if="vendors.length">
                    <template x-for="(vendor,index) in vendors" :key="index">
                        <tr>
                            <th scope="row" x-text="index+1"></th>
                            <td x-text="vendor.name"></td>
                            <td x-text="vendor.email"></td>
                            <td x-text="vendor.shop_name"></td>
                            <td x-text="vendor.current_year_sale"></td>
                            <td x-text="vendor.commision_discount"></td>
                        </tr>
                    </template>
                </template>

                </tbody>
            </table>
            <pre>
                <code>
        if ($current_year_sale > 1000) {
            $commission_discount = 100;
        } elseif ($current_year_sale > 500) {
            $commission_discount = 20;
        } elseif ($current_year_sale > 100) {
            $commission_discount = 10;
        } else {
            $commission_discount = 0;
        }</code>
            </pre>
        </div>
        <div class="col">
            <h1>Products</h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Price</th>
                    <th scope="col">Vendor Name</th>
                    <th scope="col">Shop name</th>
                    <th scope="col">%Commision</th>
                    <th>Buy</th>
                </tr>
                </thead>
                <tbody>
                <template x-if="products.length">
                    <template x-for="(product,index) in products" :key="index">
                        <tr>
                            <th scope="row" x-text="index+1"></th>
                            <th scope="row" x-text="product.title"></th>
                            <th scope="row" x-text="product.ammount"></th>
                            <th scope="row" x-text="product.name"></th>
                            <th scope="row" x-text="product.shop_name"></th>
                            <th scope="row" x-text="`${product.commission_percent}%`"></th>
                            <th>
                                <form action="post" @submit.prevent="buyProduct(product.id)">
                                    <button>Buy</button>
                                </form>
                            </th>


                        </tr>
                    </template>
                </template>
                <template x-if="!products.length">
                    <tr>
                        <td colspan="5">No data found!</td>
                    </tr>
                </template>

                </tbody>
            </table>
        </div>


    </div>
    <div class="row">
        <div class="col">
            <h1>Sale History</h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Vendor</th>
                    <th scope="col">Shop Name</th>
                    <th scope="col">Original Amount</th>
                    <th scope="col">Commision Amount</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">Commission Percent Original</th>
                    <th scope="col">Commission Percent Final</th>
                    <th scope="col">Return</th>
                </tr>
                </thead>
                <tbody>
                <template x-if="saleHistory.length">
                    <template x-for="(item,index) in saleHistory" :key="index">
                        <tr>
                            <th scope="row" x-text="index+1"></th>
                            <td x-text="item.title"></td>
                            <td x-text="item.name"></td>
                            <td x-text="item.shop_name"></td>
                            <td x-text="item.orignal_amount"></td>
                            <td x-text="item.commison_ammount"></td>
                            <td x-text="item.total_amount"></td>
                            <td x-text="`${item.commission_percent_original}%`"></td>
                            <td x-text="`${item.commission_percent_final}%`"></td>
                            <th>
                                <template x-if="!['Returned','Refunded'].includes(item.order_status)">
                                    <form action="post" @submit.prevent="returnProduct(item.id)">
                                        <button>Return</button>
                                    </form>
                                </template>
                                <template x-if="['Returned','Refunded'].includes(item.order_status)">
                                    <span>N/A</span>
                                </template>
                            </th>
                        </tr>
                    </template>
                </template>

                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    function multiCart() {
        return {
            vendors: [],
            products: [],
            saleHistory: [],
            init() {
                this.allVendors()
                this.allProducts()
                this.salesHistory()
            },
            allVendors() {
                fetch('<?= base_url('all-vendors') ?>')
                    .then(data => data.json())
                    .then(response => {
                            this.vendors = response;
                        }
                    )
                    .catch(e => console.log(e))
            },
            allProducts() {
                fetch('<?= base_url('all-products') ?>')
                    .then(data => data.json())
                    .then(response => {
                            this.products = response;
                        }
                    )
                    .catch(e => console.log(e))

            },
            salesHistory() {
                fetch('<?= base_url('sale-history') ?>')
                    .then(data => data.json())
                    .then(response => {
                            this.saleHistory = response;
                        }
                    )
                    .catch(e => console.log(e))


            },
            buyProduct(id) {
                let fromData = new FormData()
                fromData.append('id', id);
                try {
                    fetch('<?= base_url('buy-products') ?>', {
                        'method': 'POST',
                        'body': fromData
                    })
                        .then(data => data.json())
                        .then(response => {
                                this.salesHistory();
                                this.allVendors();
                            }
                        )
                        .catch(e => console.log(e))

                } catch (err) {
                    console.log(err)
                }
            },
            returnProduct(id) {
                let fromData = new FormData()
                fromData.append('id', id);
                try {
                    fetch('<?= base_url('return-product') ?>', {
                        'method': 'POST',
                        'body': fromData
                    })
                        .then(data => data.json())
                        .then(response => {
                                this.salesHistory();
                                this.allVendors();
                            }
                        )
                        .catch(e => console.log(e))

                } catch (err) {
                    console.log(err)
                }
            },
            reset() {
                fetch('<?= base_url('reset') ?>', {
                    'method': 'POST',
                    'body': {}
                })
                    .then(data => data.json())
                    .then(response => {
                            this.salesHistory();
                            this.allVendors();
                        }
                    )
                    .catch(e => console.log(e))
            }
        }
    }

</script>

<!-- -->

</body>
</html>
