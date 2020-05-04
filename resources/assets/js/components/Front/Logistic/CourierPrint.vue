<template>
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Принт форм</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="display: none;">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Курьер</label>

                            <div class="col-sm-10">
                                <v-select label="name"
                                          index="id"
                                          :options="couriers"
                                          v-model="courierId">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Дата</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" v-model="date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button class="btn btn-success" @click="onRouteList" :disabled="!valid">
                    <i class="fa fa-file-text"></i> Маршрутный лист
                </button>
                <button class="btn btn-warning" @click="onRouteList($event, true)" :disabled="!valid">
                    <i class="fa fa-search"></i> Маршрутный лист
                </button>

                <button class="btn btn-success" @click="onCheckList" :disabled="!valid">
                    <i class="fa fa-file-text"></i> Расписка
                </button>
                <button class="btn btn-warning" @click="onCheckList($event, true)" :disabled="!valid">
                    <i class="fa fa-search"></i> Расписка
                </button>
            </div>
          </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        data() {
            return {
                courierId: null,
                date: null,
                couriers: []
            }
        },

        methods: {
            onCheckList(event, isShow=false){
                window.open(`/documents/courier-check-list/${this.courierId}?date=${this.date}` + (isShow ? '&show=1' : ''));
            },
            onRouteList(event, isShow=false){
                window.open(`/documents/route-list/${this.courierId}?date=${this.date}` + (isShow ? '&show=1' : ''));
            },
            async getCouriers(){
                let response = await axios.get(`/courier/get`);
                this.couriers = response.data;
            },
        },

        created(){
            this.getCouriers();
            this.date = window.moment().format("YYYY-MM-DD");
        },

        computed:{
            valid(){
                return this.courierId !== null && this.date !== null;
            }
        }
    }
</script>