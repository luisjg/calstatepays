<template>
    <v-dialog width="800" v-model="dialog" >
    <v-card>
        <v-card-title
        class="headline grey lighten-2  row  no-gutters"
        primary-title
        >
        <h1 v-if="university" class="col-11"> Market Outcomes for  {{university.name}} </h1>
        <i role="button"   @click="closeModal();" class="fa fa-times text-center col-1 "></i>
        </v-card-title>
        <v-card-text >
                <v-progress-circular v-if="tableauIsLoading" class="tableau-loading-icon"
                    :size="100"
                    :width="10"
                    indeterminate
            ></v-progress-circular>
            <div v-bind:class="{'tableau-loading':tableauIsLoading}">
                <div :class="(majorsDisplayIsAllowed) ? 'row': 'row tableau-loading'">
                    <i class="col-3 fa fa-university fa-5x" ></i> 
                    <div class="col-9">
                        <span class="d-block">Earnings by Major + Industries of Employment</span>
                            <button v-if="majorsDisplayIsAllowed" @click="chooseTableauCategory(university.id,0)"  type="button" class="power-user-modal-btn btn-success">View Data</button>
                            <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>           
                    </div>
                </div>
                <v-divider></v-divider> 
                <div :class="(ageDisplayIsAllowed) ? 'row': 'row tableau-loading'">
                    <div class="col-3">
                        <i class="fa fa-child fa-2x"></i>
                        <i class="fa fa-male fa-5x"></i>
                    </div>
                    <div class="col-9">
                        <span class="d-block">Earnings by Age at Entry + Industries of Employment</span>
                        <button v-if="ageDisplayIsAllowed" @click="chooseTableauCategory(university.id,1)"  type="button" class=" power-user-modal-btn btn-success">View Data</button>
                        <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>            
                    </div>
                </div>            
                <v-divider></v-divider>
                <div :class="(raceDisplayIsAllowed) ? 'row': 'row tableau-loading'">
                    <div class="col-3">
                        <i class="fa fa-male fa-5x brown--text"></i>
                        <i class="fa fa-male fa-5x blue--text"></i>
                    </div>
                    <div class="col-9">
                        <span class="d-block">Earnings by Race + Industries of Employment</span>
                            <button v-if="raceDisplayIsAllowed" @click="chooseTableauCategory(university.id,2)"  type="button" class=" power-user-modal-btn btn-success">View Data</button>
                            <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>           
                    </div>
                </div>
                <v-divider></v-divider>
                <div :class="(genderDisplayIsAllowed) ? 'row': 'row tableau-loading'"> 
                    <div class="col-3">
                        <i class="fa fa-mars fa-5x"></i>
                        <i class="fa fa-venus fa-5x"></i>
                    </div>               
                    <div class="col-9">
                        <span class="d-block">Earnings by Gender + Industries of Employment</span>
                            <button v-if="genderDisplayIsAllowed" @click="chooseTableauCategory(university.id,3)" type="button" class="power-user-modal-btn btn-success">View Data</button>
                            <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>           
                    </div>
                </div >
                <v-divider></v-divider>
                <div :class="(pellDisplayIsAllowed) ? 'row': 'row tableau-loading'">
                    <div class="col-3">
                        <i class="fa fa-check fa-4x text-success"></i>
                        <i class="fa fa-times fa-4x text-danger"></i>
                    </div>
                    <div class="col-9">
                        <span class="d-block">Earnings by Pell Status at Entry + Industries of Employment</span>
                            <button v-if="pellDisplayIsAllowed" @click="chooseTableauCategory(university.id,4)" type="button" class="power-user-modal-btn btn-success">View Data</button>
                            <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>          
                    </div>
                </div>
                <v-divider></v-divider>
                <div :class="(credentialDisplayIsAllowed) ? 'row': 'row tableau-loading'">
                    <div class="col-3">
                        <i class="fa fa-certificate fa-5x text-warning" aria-hidden="true"></i>
                    </div>
                    <div class="col-9">
                        <span class="d-block">Earnings by Credential Type</span>
                            <button v-if="credentialDisplayIsAllowed" @click="chooseTableauCategory(university.id,5)" type="button" class="power-user-modal-btn btn-success">View Data</button>
                            <button v-else type="button" class="power-user-modal-btn btn-success btn-locked">View Data</button>          
                    </div>
                </div>
            </div>
        </v-card-text>
        <v-divider></v-divider>
    </v-card>
    </v-dialog>
</template>
<script>
import {mapGetters,mapActions} from 'vuex';
export default {
    props:['showModal','university'],
    name: 'power-users-modal',
        data() {
            return {
                str:'researchcsun',
                tabl:'CSU7LaborMarketOutcomes-ByMajor/CSU7AggregareEarningsData'
        }
    },
    created() {
        document.addEventListener('keyup', this.onEscKey)
    },
    methods: {
        closeModal:function() {
            this.$emit('closeModal')
        },
        onEscKey(event) {
            if( event.keyCode === 27 ) {
                this.closeModal()
            }
        },
        chooseTableauCategory(university, path_id) {
            let tableauObj = {
                // "iframe_server":this.optInValues[university][path_id].iframe_server,
                "iframe_string":this.optInValues[university][path_id].iframe_string
            } 
            sessionStorage.setItem('tableauValue', tableauObj["iframe_string"]);
            this.$store.dispatch('setTableauValue', tableauObj["iframe_string"]);
            this.$router.push({name:'tableau' , params:{tableauValue:this.tableauValue}});
        }
    },
    computed: {
        ...mapGetters([
            "universityById",
            "tableauValue",
            "tableauIsLoading",
            "optInValues"
        ]),
        majorsDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[0] != undefined && currentValues[0].opt_in != undefined) {
                    currentOptInValue = currentValues[0].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        ageDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[1] != undefined && currentValues[1].opt_in != undefined) {
                    currentOptInValue = currentValues[1].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        raceDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[2] != undefined && currentValues[2].opt_in != undefined) {
                    currentOptInValue = currentValues[2].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        genderDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[3] != undefined && currentValues[3].opt_in != undefined) {
                    currentOptInValue = currentValues[3].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        pellDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[4] != undefined && currentValues[4].opt_in != undefined) {
                    currentOptInValue = currentValues[4].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        credentialDisplayIsAllowed: function() {
            var currentUniversityId; 
            var currentOptInValue;
            if(this.university != undefined && this.optInValues['0'] != undefined) {
                currentUniversityId = this.university.id;
                var currentValues = this.optInValues[currentUniversityId];
                if (currentValues[5] != undefined && currentValues[5].opt_in != undefined) {
                    currentOptInValue = currentValues[5].opt_in;
                    return currentOptInValue === 1 ? true :false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        },
        dialog: {
            get:function() {
                return this.showModal;
            },
            set:function() {
                this.$emit('closeModal',false)
            }
        },   
    }
    
}
</script>