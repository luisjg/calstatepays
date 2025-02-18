<template>
	<form class="container-fluid csu-card__form">
		<fieldset class="csu-card__form-sizing">
			<div v-if="this.formNotFilled" class="form-group">
				<div v-bind:class="[this.formNotFilled ? 'required-field' : 'required-field--hidden']">
					<i class="fa fa-exclamation-circle"></i> Please fill out all required fields.
				</div>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="fieldOfStudy">Select a Discipline (Optional)</label>
				<v-select
					label="discipline"
					aria-label="Select Discipline Optional"
					:options="fieldOfStudies"
					@input="updateGrandfatherSelect('fieldOfStudyId', 'id', $event); selected.majorName = null"
					class="csu-form-input"
				></v-select>
			</div>
			<div class="form-group">
				<label
					class="font-weight-bold"
					for="Major"
					v-bind:style="[this.submitted && !this.form.major ? errorLabel : '']"
				>Select a Major</label>
				<v-select
					label="major"
					aria-label="Select a Major"
					v-model="selected.majorName"
					:options="this.form.fieldOfStudyId == null ? majors : pfreMajorsByField"
					@input="updateGrandfatherSelect('major', 'major', $event)"
					@change="updateGrandfatherSelect('major', 'major', $event)"
					class="csu-form-input"
					:loading="pfreDisciplineLoad"
					v-bind:class="{'border-danger': this.submitted && !this.form.major}"
				></v-select>
			</div>
			<div class="form-group">
				<label
					class="form-group font-weight-bold"
					for="education"
					v-bind:style="[this.submitted && !this.form.education ? errorLabel : '']"
				>How You Entered the CSU <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Select First Time Freshman if you plan to enter directly from High School, or select First Time Transfer if you plan to transfer from a Community College or other institution."></i></label>
				<div class="row">
					<div class="col-sm-6 col-lg-12">
						<button
							class="pfre-btn font-weight-bold"
							:class="{'pfre-btn--selected font-weight-bold': this.form.education == 'FTF', '':this.form.education != 'FTF'}"
							@click.prevent="setEducationLevel('FTF')"
						>First Time Freshman</button>
					</div>
					<div class="col-sm-6 col-lg-12">
						<button
							class="pfre-btn font-weight-bold"
							:class="{'pfre-btn--selected font-weigth-bold': this.form.education == 'FTT', '':this.form.education != 'FTT'}"
							@click.prevent="setEducationLevel('FTT')"
						>First Time Transfer</button>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label
					class="font-weight-bold"
					for="earnings"
					v-bind:style="[this.submitted && this.form.earnings === null ? errorLabel : '']"
				>Estimated Annual Earnings While Attending the CSU <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Click on the range that best estimates how much you expect to earn each year while you are enrolled. Many students work while attending college. This reduces the earnings you miss out on while in college and increases your personal financial return."></i></label>
				<v-select
					label="earn"
					aria-label="Estimated Annual Earnings In School"
					:options="earningRanges"
					v-model="selected.earnings"
					@input="updateSelect('earnings', $event)"
					@change="updateSelect('earnings', $event)"
					class="csu-form-input"
					v-bind:class="{'border-danger': this.submitted && this.form.earnings === null }"
				></v-select>
			</div>
			<div class="form-group">
				<label
					for="financialAid"
					v-bind:style="[this.submitted && this.form.financialAid === null ? errorLabel : '']"
					class="font-weight-bold"
				>Estimated Annual Financial Aid (Excluding Loans) <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Click on the range that best represents the amount of money you expect to receive as grants or scholarhips (such as Cal Grants, Pell Grants, veterans benefits or employer reimbursement) in a year. Do not include student loans. Receiving grants and scholarships lowers the cost of attending and increases your personal financial return."></i></label>
				<v-select
					label="finAid"
					aria-label="Estimated Annual Financial Aid Excluding Loans"
					:options="financialAidRanges"
					v-model="selected.financialAid"
					@input="updateSelect('financialAid', $event)"
					@change="updateSelect('financialAid', $event)"
					class="csu-form-input"
					v-bind:class="{'border-danger': this.submitted && this.form.financialAid === null}"
				></v-select>
			</div>
			<div class="row row--condensed" id="submit-btn-container">
				<div class="py-2">
					<button
						id="submit-btn"
						type="button"
						class="btn btn-success btn-submit"
						@click="submitForm()"
					>Submit</button>
				</div>
			</div>
		</fieldset>
	</form>
</template>
<script>
import vSelect from "vue-select";
import $ from "jquery";
import { updateForm } from "../../utils/index";
import { required } from "vuelidate/lib/validators";
import { mapGetters, mapActions } from "vuex";

export default {
	mounted() {
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
	},
	data() {
		return {
			formNotFilled: false,
			submitted: false,
			selected: {
				majorName: null,
				education: null,
				earnings: null,
				financialAid: null
			},
			form: {
				fieldOfStudyId: null,
				major: null,
				education: null,
				earnings: null,
				financialAid: null
			},

			errorLabel: {
				color: "red",
				fontWeight: "bold"
			},

			earningRanges: [
				{ earn: "$0 - $2,250", value: 0 },
				{ earn: "$2,251 - $7,750", value: 4500},
				{ earn: "$7,751+", value: 10500 }
			],
			financialAidRanges: [
				{ finAid: "$0 - $1,500", value: 'fin_aid_0' },
				{ finAid: "$1,501 - $6,500", value: 'fin_aid_3000' },
				{ finAid: "$6,501+", value: 'fin_aid_10000' },
				]
		};
	},
	methods: {
		...mapActions(["fetchFreData", "fetchPfreMajorsByField"]),
		updateGrandfatherSelect(field, dataKey, data) {
			this.submitted = false;
			if (data) {
				this.form[field] = data[dataKey];
				this.handleFieldOfStudyMajors(field);
			} else {
				this.form[field] = null;
			}
		},
		handleFieldOfStudyMajors(field) {
			if (field == "fieldOfStudyId") {
				this.fetchPfreMajorsByField({
					form: this.form,
					school: this.selectedUniversity
				});
			}
		},
		updateSelect(field, data) {
			if (data) {
				this.form[field] = data.value;
			} else {
				this.form[field] = null;
			}
		},
		setEducationLevel(data) {
			this.form.education = data;
			this.selected.education = data;
		},
		scrollWin() {
			if (window.innerWidth <= 767) {
				var scrollTop;
				var progressBar = document.getElementById(
					"submit-btn-container"
				);
				progressBar.scrollIntoView({
					behavior: "smooth",
					block: "start",
					inline: "end"
				});
			}
		},
		submitForm() {
			this.formNotFilled = false;
			this.submitted = true;
			if (this.checkForm()) {
				this.scrollWin();
				document.getElementById("submit-btn").innerHTML = "Resubmit";
				this.$store.dispatch("submitPfreForm");
				this.$store.dispatch("setPfreSelections", this.selected);
				this.fetchFreData({
					...this.form,
					school: this.selectedUniversity
				});
			}
		},
		checkForm() {
			if (this.$v.$invalid) {
				this.formNotFilled = true;
				return false;
			} else return true;
		}
	},
	computed: {
		...mapGetters([
			"majors",
			"selectedUniversity",
			"fieldOfStudies",
			"selectedUniversity",
			"pfreMajorsByField",
			"pfreDisciplineLoad"
		]),
	},
	validations: {
		form: {
			major: { required },
			education: { required },
			earnings: { required },
			financialAid: { required }
		}
	},
	components: {
		vSelect
	}
};
</script>
