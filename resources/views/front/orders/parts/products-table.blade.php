<products-table :initial_data="{{ json_encode($order->products, true) }}" :initial_order="{{ json_encode($order->id, true) }}" inline-template>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Товары</h3>

        <div class="box-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
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
                    <td>@{{ index + 1  }}</td>
                    <td>
                        {{--@{{  products[index].pivot.quantity }} --}}
                        <input type="number" class="form-control" min="1" v-model="products[index].pivot.quantity">
                    </td>
                    <td> @{{  products[index].product_name }} </td>
                    <td>
                        {{--@{{  products[index].pivot.imei }}--}}
                        <input type="text" class="form-control" v-model="products[index].pivot.imei">
                    </td>
                    <td>
                        {{--@{{  products[index].pivot.price }}--}}
                        <input type="number" class="form-control" min="1" v-model="products[index].pivot.price">
                    </td>
                    <td>
                        {{--@{{  products[index].pivot.price_opt }}--}}
                        <input type="number" class="form-control" min="1" v-model="products[index].pivot.price_opt">
                    </td>
                    <td> Поставщик  </td>
                    <td>
                        {{--@{{  products[index].pivot.courier_payment }}--}}
                        <input type="number" class="form-control" min="1" v-model="products[index].pivot.courier_payment">
                    </td>
                    <td>
                        @{{  delta(index) }}
                    </td>
                    <td>
                        <i class="btn btn-danger fa fa-trash" @click.prevent="deleteProduct(index)"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-success pull-right" @click.prevent="addProduct()">
                            <i class="fa fa-plus"></i>Добавить
                        </button>
                    </td>
                    <td></td>
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
</products-table>