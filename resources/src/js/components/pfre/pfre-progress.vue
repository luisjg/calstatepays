<template>
	<div>
		<div v-if="pfreIsLoading" class="row">
			<v-progress-circular class="loading-icon" :size="100" :width="10" indeterminate></v-progress-circular>
		</div>
		<div v-else>
			<div v-if="!this.pfreFormWasSubmitted">
				<h3 class="industry-title text-center p-md-3">Please make your selection</h3>
				<p class="lead pl-md-5 pr-md-5">
					You have the option of either filtering out majors by
					<span class="font-weight-bold">discipline</span> or choosing the
					<span class="font-weight-bold">major</span>
					which resonates the most with you.
				</p>
				<p class="lead pl-md-5 pr-md-5">
					<span class="font-weight-bold">Please Note:</span> Some majors might not have any data available at the moment.
					For more information on how we gathered the data, please read the
					<router-link to="/faq" class="font-weight-bold">FAQ</router-link>.
				</p>
				<div class="pl-md-5 pr-md-5 iframe-container">
					<iframe width="560" height="315" src="https://www.youtube.com/embed/FAmlV9qvM7o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div>
			<div v-else id="progress-bars">
				<div class="row">
					<div class="col-12">
						<h3 class="pfre-info__majorTitle">
							{{this.pfreSelected.majorName}}
						</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<ul class="pfre-info__listGroup">
							<li class="pfre-info__listElement">
								<strong class="csu-card__tags">Education Level:</strong>
								<span v-if="this.pfreSelected.education == 'Freshman'">
									First Time Freshman
								</span>
								<span v-else-if="this.pfreSelected.education == 'Transfer'">
									First Time Transfer
								</span>
							</li>
							<li class="pfre-info__listElement">
								<strong class="csu-card__tags">Earnings:</strong>
								{{this.pfreSelected.earnings}}
							</li>
							<li class="pfre-info__listElement">
								<strong class="csu-card__tags">Financial Aid:</strong>
								{{this.pfreSelected.financialAid}}
							</li>
						</ul>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-12">
						<h4 class="pfre-info__title">Annual Personal Financial Return on Your Education</h4>
						<p v-if="pfreData.returnOnInvestment != 'No data.'" class="pfre-info__percentage">
							{{pfreData.returnOnInvestment}}
						</p>
						<p v-else class="pfre-info__noPercentage">
							No Data
						</p>
						<p class="pfre-info__infoCopy">
							The PFRE is your Personal Financial Return on Education for completing a Bachelor's
							Degree from the CSUs for the major you chose. This number is like an interest rate, so a higher
							PFRE means you are getting a better deal.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { currency, percentage } from "../../filters";
import { mapGetters, mapActions } from "vuex";
import pfreInfo from "./pfre-info.vue";
export default {
	data() {
		return {
			userYears: {
				start: 0,
				middle: 0,
				end: 0,
				actual: 0
			}
		};
	},
	computed: {
		...mapGetters([
			"pfreData",
			"pfreFormWasSubmitted",
			"pfreSelected",
			"pfreIsLoading"
		]),
		smallestScreen() {
			var width = window.innerWidth;
			return width > 500;
		}
	},
	methods: {
		...mapActions(["fetchFreData", "toggleInfo"])
	},
	filters: { percentage, currency },
	components: { pfreInfo }
};
</script>
