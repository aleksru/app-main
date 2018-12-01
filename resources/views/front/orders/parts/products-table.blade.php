<products-table :initial_data="{{ json_encode($order->products, true) }}" :initial_order="{{ json_encode($order->id, true) }}" inline-template>
<div>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Добавление товаров</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <label for="name" class="col-sm-2">Найти товар</label>

                <div class="col-sm-6">
                    <v-select label="product_name" v-model="selectedProduct" :filterable="false" :options="productSearch" @search="onSearchProduct"></v-select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" @click.prevent="addProduct()">
                        <i class="fa fa-plus"></i>Добавить
                    </button>
                </div>
            </div>
            <br/>
            <div class="row" v-show="showCreateProduct">
                <label for="name" class="col-sm-2 control-label">Создать новый товар</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="new_product" v-model="newProductName">
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-primary" @click.prevent="createProduct()">
                        <i class="fa fa-shopping-bag"></i>Создать товар
                    </button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Товары</h3>

            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 1000px;">
                    {{--<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">--}}

                    <div class="input-group-btn">
                        <div class="col-sm-12">
                            <button class="btn btn-primary pull-right" @click.prevent="submit()">
                                <i class="fa fa-save"></i> Сохранить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Кол-во</th>
                    <th>Модель</th>
                    <th>IMEI</th>
                    <th>Цена</th>
                    <th>Закупка</th>
                    <th>Поставщик</th>
                    <th>Зп Курьера</th>
                    <th>Прибыль</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, index) in products">
                        <td style="width: 2%">@{{ index + 1  }}</td>
                        <td style="width: 5%">
                            {{--@{{  products[index].pivot.quantity }} --}}
                            <input type="number" min="1" style="width: 100%" v-model="products[index].pivot.quantity">
                        </td>
                        <td style="width: 15%"> @{{  products[index].product_name }} </td>
                        <td style="width: 10%">
                            {{--imei --}}
                            <input type="text" class="form-control" v-model="products[index].pivot.imei">
                        </td>
                        <td style="width: 10%">
                            {{--price --}}
                            <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].pivot.price">
                        </td>
                        <td style="width: 10%">
                            {{--price_opt --}}
                            <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].pivot.price_opt">
                        </td>
                        <td style="width: 10%">
                            <v-select label="name" v-model="products[index].supplier_in_order[0]" :options= {{ json_encode( \App\Models\Supplier::select('id', 'name')->get()) }}></v-select>
                        </td>
                        <td style="width: 10%">
                            {{--courier_payment --}}
                            <input type="number" class="form-control" min="1" style="width: 100%" v-model="products[index].pivot.courier_payment">
                        </td>
                        <td style="width: 5%">
                            @{{  delta(index) }}
                        </td>
                        <td style="width: 5%">
                            <i class="btn btn-danger fa fa-trash" @click.prevent="deleteProduct(index)"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td>

                        </td>
                        <td></td>
                        <td>Итого</td>
                        <td>@{{ summ_price_products }}</td>
                        <td>@{{ summ_opt }}</td>
                        <td>  </td>
                        <td> @{{ courier_payments }} </td>
                        <td> @{{ profit }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</products-table>