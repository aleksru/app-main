<template>
    <div>
        <div class="box-header with-border">
            <div class="col-md-2">
                <button id="btn_activate_call_center"
                        type="button" class="btn btn-block btn-default"
                        :disabled="!isComplaint"
                        @click="changeTypeCalls()">Коллцентр
                </button>
            </div>
            <div class="col-md-2">
                <button id="btn_activated_complaint"
                        type="button"
                        class="btn btn-block btn-default"
                        :disabled="isComplaint"
                        @click="changeTypeCalls()">Рекламации
                </button>
            </div>
            <div class="col-md-2">
                <input type='date' v-model="forDate" @input="updateTable()"/>
            </div>

            <div class="col-md-2">
                <small class="label bg-red" style="font-size: 150%;">{{uniqueCalls}}</small>
            </div>

            <div class="col-md-4">
                <div class="col-md-4">
                    <small title="Кол-во чистых пропущенных" class="label bg-green" style="font-size: 150%;">{{countSimples}}</small>
                    <span class="badge bg-green" style="font-size: 100%;">{{percentSimples}}</span>
                </div>

                <div class="col-md-4">
                    <small title="Кол-во пропущенных в рекламации" class="label bg-yellow" style="font-size: 150%;">{{countReclamations}}</small>
                    <span class="badge bg-yellow" style="font-size: 100%;">{{percentReclamation}}</span>
                </div>

                <div class="col-md-4">
                    <small title="Общее кол-во пропущенных" class="label bg-navy" style="font-size: 150%;">{{sumMissed}}</small>
                </div>
            </div>


        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Клиент</th>
                        <th>Телефон</th>
                        <th>Магазин</th>
                        <th>Время</th>
                    </tr>
                    <tr v-for="(call, index) in missedCalls" :key="call.id" v-if="! isComplaintCall(index)" v-show="!isComplaint">
                        <td>
                            <a :href="'/clients/' + call.client_id" target="_blank">
                                {{call.client.name}}
                                <i class="fa fa-address-card-o" aria-hidden="true"></i>
                            </a>

                        </td>
                        <td>
                            <div class="margin">
                                <div class="btn-group">
                                    <input type="button"
                                           class="btn btn-default btn-call-info"
                                           data-toggle="dropdown"
                                           aria-haspopup="true"
                                           aria-expanded="false"
                                           :value="call.from_number"
                                           @click="processAllCallsPhone(call.from_number, call.id)"/>
                                    <ul class="dropdown-menu">
                                        <li role="presentation" v-for="(otherCall, name) in phoneOtherCalls[call.id]">
                                            <a role="menuitem" tabindex="-1" href="#" onclick="return false">
                                                <p :class="otherCall.type === 'INCOMING' ? 'text-red' : 'text-green'">
                                                    {{formatDateTimeCall(otherCall.call_create_time)}}
                                                    {{otherCall.store ? otherCall.store.name : ''}}
                                                    {{otherCall.type === 'INCOMING' ? 'Входящий' : 'Исходящий'}}
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <call-back v-if="operator && call.store"
                                           :phones="[call.from_number]"
                                           :operator="operator"
                                           :store="call.store"
                                >
                                </call-back>
                            </div>
                        </td>
                        <td>{{call.store ? call.store.name : ''}}</td>
                        <td>{{formatDateTimeCall(call.call_create_time)}}</td>
                    </tr>
                    <tr v-for="(call, index) in missedCalls" :key="call.id" v-if="isComplaintCall(index)" v-show="isComplaint">
                        <td>
                            <a :href="'/clients/' + call.client_id" target="_blank">
                                {{call.client.name}}
                                <i class="fa fa-address-card-o" aria-hidden="true"></i>
                            </a>

                        </td>
                        <td>
                            <div class="margin">
                                <div class="btn-group">
                                    <input type="button"
                                           class="btn btn-default btn-call-info"
                                           data-toggle="dropdown"
                                           aria-haspopup="true"
                                           aria-expanded="false"
                                           :value="call.from_number"
                                           @click="processAllCallsPhone(call.from_number, call.id)"/>
                                    <ul class="dropdown-menu">
                                        <li role="presentation" v-for="(otherCall, name) in phoneOtherCalls[call.id]">
                                            <a role="menuitem" tabindex="-1" href="#" onclick="return false">
                                                <p :class="otherCall.type === 'INCOMING' ? 'text-red' : 'text-green'">
                                                    {{formatDateTimeCall(otherCall.call_create_time)}}
                                                    {{otherCall.store ? otherCall.store.name : ''}}
                                                    {{otherCall.type === 'INCOMING' ? 'Входящий' : 'Исходящий'}}
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <call-back v-if="operator && call.store"
                                           :phones="[call.from_number]"
                                           :operator="operator"
                                           :store="call.store"
                                >
                                </call-back>
                            </div>
                        </td>
                        <td>{{call.store ? call.store.name : ''}}</td>
                        <td>{{formatDateTimeCall(call.call_create_time)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>

<script>
    export default {
        props: {
            operator: [Object, null],
            complaintNumbers: Array
        },

        data() {
            return {
                missedCalls: [],
                isComplaint: false,
                phoneOtherCalls: {},
                forDate: null,
                uniqueCalls: 0,
                countSimples: 0,
                countReclamations:0
            }
        },

        methods: {
            async getMissedCalls(){
                let res = await this.$axios.get('calls-table', {
                    params: {
                        forDate: this.forDate,
                    }
                });

                return res;
            },

            async getCallsByPhone(phone) {
                let res = await this.$axios.post("/calls/get-calls-by-phone", {
                    phone: phone
                });

                return res;
            },

            processAllCallsPhone(phone, key){
                if (this.phoneOtherCalls[key]){
                    return false;
                }
                this.getCallsByPhone(phone).then((res) => {
                    this.$set(this.phoneOtherCalls, key, res.data.calls);
                });
            },

            changeTypeCalls(){
                this.isComplaint = ! this.isComplaint;
                //this.updateTable();
            },

            updateTable(){
                this.getMissedCalls().then((res) => {
                    this.missedCalls = res.data.calls;
                    this.uniqueCalls = res.data.uniquePhones;
                    this.countSimples = res.data.countSimples;
                    this.countReclamations = res.data.countReclamations;
                }).catch((err) => {
                    console.log(err);
                });
            },

            formatDateTimeCall(timestamp){
                return moment.unix(timestamp).format('DD.MM HH:mm:ss');
            },

            isComplaintStore(index){
                let storeId = this.missedCalls[index].store_id;
                let storeComplaints = this.missedCalls[index].client.store_complaints;
                let findIndex = storeComplaints.findIndex(storeComplaint => parseInt(storeId) === parseInt(storeComplaint.store_id));

                return findIndex > -1;
            },

            isComplaintCall(index){
                let numb = parseInt(this.missedCalls[index].extension);
                return this.complaintNumbers.indexOf(numb) > -1 || this.isComplaintStore(index);
            }

        },

        mounted(){
            this.updateTable();
            window.Echo.channel('missed-calls')
                .listen('MissedCallEvent', (e) => {
                    this.updateTable();
                })
        },

        computed: {
            sumMissed(){
                return this.countSimples + this.countReclamations
            },
            percentSimples(){
                return this.sumMissed > 0 ? Math.round(this.countSimples / this.sumMissed * 100) + '%' : 0;
            },
            percentReclamation(){
                return this.sumMissed > 0 ? Math.round(this.countReclamations / this.sumMissed * 100) + '%' : 0;
            }
        }
    }
</script>