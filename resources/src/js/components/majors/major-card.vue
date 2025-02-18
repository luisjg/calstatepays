<template>
	<div>
		<div class="row pt-md-2" v-bind:id="'majorCardHasIndex-' + this.index">
			<aside class="col-md-3">
				<major-form :windowWidth="windowWidth" :index="index" />
			</aside>
			<div class="col-md-9">
				<card v-if="selectedFormWasSubmittedOnce" class="csu-card container-fluid">
					<div v-if="selectedMajorIsLoading" class="form-group row">
						<v-progress-circular class="loading-icon"
               			 :size="100"
                		:width="10"
                		indeterminate
                		></v-progress-circular>
					</div>
					<div v-else>
							<div class="row">
							<div class="col">
								<i class="col-1 fa fa-times fa-2x btn-remove text-right pull-right" @click.prevent="removeCurrentCard" v-show="isNotFirstCard" title="Close"/>
								<social-sharing 
								v-if="selectedFormWasSubmittedOnce"
								:class="{'invisible': nullValues}"
								:url="this.url"
								:title="this.shareDescription"
								description="Discover Your Earnings After College." 
								:quote="this.shareDescription" 
								hashtags="CalStatePays, ItPaysToGoToCollege"
								inline-template>
									<div>
										<network network="twitter" class="csu-card__share csu-card__share-twitter float-right">
											<i class="fa fa-twitter-square"></i>
											Tweet
										</network>
										<network network="linkedin" class="csu-card__share csu-card__share-linkedin float-right">
											<i class="fa fa-linkedin-square"></i>
											Share
										</network>
										<network network="facebook" class="csu-card__share csu-card__share-facebook float-right">
											<i class="fa fa-facebook-official"></i>
											Share
										</network>
									</div>
								</social-sharing>
							</div>
						</div>
						<div class="row">
							<h2 class="major-title pt-3">{{selectedMajorTitle}}</h2>
						</div>
											<div class="row">
								<div class="col-12">
									<major-legend v-show="selectedFormWasSubmittedOnce" :educationLevel="selectedEducationLevel"></major-legend>
								</div>
							</div>
							<div v-show="selectedFormWasSubmittedOnce && nullValues" class="csu-card__no-data">
								<p class="lead pl-5 pr-5">No data is available for this selected Degree Level.</p>
								<p class="lead pl-5 pr-5">Please see the <router-link to="/faq" class="font-weight-bold">FAQ</router-link> section for more information on how we collected the data.</p>
							</div>
						<div v-show="!nullValues">
							<div class="row">
								<div class="col-12">
									<major-graph-wrapper v-bind:id="'majorGraphWrapperIndex-' + this.index" :majorData="selectedMajorData" :majorTitle="selectedMajorTitle"
									:educationLevel="selectedEducationLevel" :windowWidth="windowWidth"></major-graph-wrapper>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<industry-carousel :empty="isEmpty" :industries="selectedIndustries" :major="selectedMajorTitle"></industry-carousel>
							</div>
						</div>

					</div>
					
				</card>
				<div v-else class="csu-card">
					<div class="row">
						<i class="col fa fa-times fa-2x btn-remove text-right pull-right" @click.prevent="removeCurrentCard" v-show="isNotFirstCard" title="Close"></i>
					</div>
					<h3 class="industry-title text-center p-md-3">Please make your selection</h3>
					<p class="lead pl-md-5 pr-md-5">
						You have the option of either filtering out majors by <strong class="font-weight-bold">discipline</strong> or choosing the <strong class="font-weight-bold">major</strong>
						you want to see.
					</p>
					<p class="lead pl-md-5 pr-md-5">
						<strong class="font-weight-bold">Please Note:</strong> Some majors might not have any data available at the moment.
						For more information on how we gathered the data, please read the <router-link to="/faq" class="font-weight-bold">FAQ</router-link>.
					</p>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import majorForm from "./major-form.vue";
import card from "../global/card";
import majorsGraph from "./majors-graph.vue";
import majorGraphWrapper from "./major-graph-wrapper.vue";
import industryCarousel from "../industries/industry-carousel.vue";
import majorLegend from "./major-legend.vue";

import { updateForm } from "../../utils/index";
import { mapGetters, mapActions } from "vuex";
export default {
	props: ["index", "windowWidth"],
	data() {
		return {
		    url: window.baseUrl,
			major: this.majorNameById
		};
	},
	computed: {
		...mapGetters([
			"industries",
			"majorData",
			"majorTitle",
			"educationLevel",
			"formWasSubmitted",
			"formWasSubmittedOnce",
			"majorNameById",
			"majors",
			"universities",
			"selectedUniversity",
			"majorIsLoading"
		]),
		isEmpty() {
			//Check whether the form field was fired off, toggle carousel on
			if (
				this.industries(this.index).length === 0 ||
				!this.selectedFormWasSubmittedOnce
			) {
				return false;
			}
			return true;
		},
		isNotFirstCard() {
			if (this.index >= 1) {
				return true;
			}
			return false;
		},
		selectedMajorData() {
			return this.majorData(this.index);
		},
		selectedIndustries() {
			return this.industries(this.index);
		},
		selectedEducationLevel() {
			return this.educationLevel(this.index);
		},
		selectedFormWasSubmitted() {
			return this.formWasSubmitted(this.index);
		},
		selectedFormWasSubmittedOnce(){
			return this.formWasSubmittedOnce(this.index);
		},
		selectedMajorIsLoading(){
			return this.majorIsLoading(this.index);
		},
		selectedMajorTitle() {
			if (this.selectedMajorData.length != 0) {
				let currentMajor = this.selectedMajorData.majorId;
				return this.majorNameById(currentMajor);
			}
		},
		shareDescription() {
			let universityFullName = this.retrieveUniversityFullName(this.universities, this.selectedUniversity);

			if(universityFullName === 'CSU 6')
					universityFullName = 'the CSU 6';

			let opening = 'I discovered that ' + this.selectedMajorTitle + ' students from '+ universityFullName+' make an average of ';

			if(this.selectedMajorData.bachelors && this.selectedEducationLevel == 'allDegrees')
				return opening + this.formatDollars(this.selectedMajorData.bachelors[5]._50th) + ' five years after graduating!';

			else if(this.selectedMajorData[this.selectedEducationLevel] && this.selectedEducationLevel == 'someCollege')
				return opening + this.formatDollars(this.selectedMajorData[this.selectedEducationLevel][5]._50th) + ' five years after dropping out of college!';

			else if(this.selectedMajorData[this.selectedEducationLevel])
				return opening + this.formatDollars(this.selectedMajorData[this.selectedEducationLevel][5]._50th) + ' five years after graduating with a ' + this.selectedEducationLevel + ' degree!';

			else
				return 'Discover your earnings after college!'
		},
		nullValues() {
			var yearsOut = [2,5,10,15]
            for(var i=0; i< yearsOut.length; i++) {
				if (this.selectedEducationLevel != "allDegrees" && this.selectedMajorData) {
                    if(this.selectedMajorData[this.selectedEducationLevel][yearsOut[i]]._25th != null ||
                    this.selectedMajorData[this.selectedEducationLevel][yearsOut[i]]._50th != null ||
                    this.selectedMajorData[this.selectedEducationLevel][yearsOut[i]]._75th != null) {
                        return false;
                    }
                } else if(this.selectedEducationLevel === "allDegrees") {
                    if(this.selectedMajorData.postBacc[yearsOut[i]]._50th != null ||
                        this.selectedMajorData.bachelors[yearsOut[i]]._50th != null ||
						this.selectedMajorData.someCollege[yearsOut[i]]._50th != null) {
                        return false;
                    }
                }
            }
			return true;
        },
		applyHiddenClass() {
		    if (this.nullValues) {
		        return 'invisible';
			}
			return '';
		}
    }, 
	methods: {
		...mapActions(["deleteMajorCard", "resetMajorCard"]),
		removeCurrentCard() {
			this.deleteMajorCard(this.index);
		},
		resetCurrentCard() {
			this.resetMajorCard(this.index);
		},
		formatDollars(input) {
			if (input) {
				let dollarAmount = input.toString();
				let hundreds = dollarAmount.substr(-3, 3);
				let thousands = dollarAmount.slice(0, -3);
				return "$" + thousands + "," + hundreds;
			}
		},
		// used for the social sharing
		retrieveUniversityFullName(universityArray,selectedUniv){
			for(var i = 0; i < universityArray.length; i++){
				if(universityArray[i].short_name === selectedUniv) return universityArray[i].name;
			}
		}
	},
	components: {
		majorForm,
		card,
		majorGraphWrapper,
		majorsGraph,
		industryCarousel,
		majorLegend
	},
};
</script>