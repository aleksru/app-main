<template>
    <div>
        <form id="user-form" role="form" method="post" class="form-horizontal" @submit.prevent="submit">
            <div class="form-group" v-for="(period, index) in periods">
                <label for="name" class="col-sm-2 control-label">Период {{ index + 1 }}
                    <i class=""
                       v-bind:class="periods[index].period == '' ?
                                                    'fa fa-exclamation-circle text-danger':
                                                    'fa fa-check-circle text-success'">
                    </i>
                </label>

                <div class="col-sm-3">
                        <label>Описание</label>
                        <input type="text" placeholder="Описание" class="form-control" :name="'periods['+ index +']'" v-model="periods[index].period">
                        <input type="hidden" class="form-control" :name="'id['+ index +']'" v-model="periods[index].id">
                </div>

                <div class="col-sm-2">
                        <label>Время ОТ</label>
                        <input type="number" min="0" max="23" placeholder="Время ОТ" class="form-control" :name="'timeFrom['+ index +']'" v-model="periods[index].timeFrom">
                </div>

                <div class="col-sm-2">
                        <label>Время ДО</label>
                        <input type="number"  min="0" max="23"  placeholder="Время ДО" class="form-control" :name="'timeTo['+ index +']'" v-model="periods[index].timeTo">

                </div>
                <div class="col-sm-2">
                    <label style="opacity: 0">DELETE</label>
                    <button type="button" class="btn btn-danger form-control" @click.prevent="deletePeriod(index)">
                        <i class="fa fa-trash"></i> Удалить
                    </button>
                </div>
            </div>

            <div class="text-left">
                <button class="btn btn-sm btn-success" @click.prevent="addPeriod">
                    <i class="fa fa-plus"></i> Создать
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            initial_data: Array,
        },
        data() {
            return {
                periods: this.initial_data,
            }
        },
        methods: {
            addPeriod(){
                this.periods.push({period: '', id: null});
            },

            submit(){
                if(!this.dones){
                    return toast.error('Проверьте все поля!');

                }
                axios.post('/admin/delivery-periods', this.periods).then(response => {
                    this.periods = response.data.periods;
                    toast.success(response.data.message);

                }).catch(error => {
                    if (error.response.status === 422) {
                        //this.errors = error.response.data.errors || {};
                    }
                });
            },

            deletePeriod(index){

                if(!this.periods[index].id){
                   return this.periods.splice(index, 1);
                }

                let id      = this.periods[index].id;
                let name    = this.periods[index].period;

                let toastID = 'toast-delete-' + id;

                toast.confirm('Вы действительно хотите удалить элемент "' + name + '"?', () => {
                    let loading = toast.loading('Идет удаление "' + name + '"');
                    axios.delete('delivery-periods/'+ id)
                        .then((response) => {
                            toast.hide(loading);
                            this.periods.splice(index, 1);
                            toast.success(response.data.message);
                        })
                        .catch((error) => {
                            console.log(error);
                            toast.hide(loading);
                            toast.error('Ошибка сервера! Пожалуйста, свяжитесь с администратором.');
                        })
                }, null, { id: toastID });
            }
        },
        computed: {
            dones(){
//                var done = true;
//
//                for(let i = 0; i < this.periods.length; i++){
//                    this.periods[i].period !== '' ? '' : done = false;
//                }

                return true;
            }
        }
    }
</script>
