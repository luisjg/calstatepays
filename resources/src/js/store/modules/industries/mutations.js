import _industries from "../../mutation-types/industries";

export default {
	[_industries.FETCH_INDUSTRIES](state, payload) {
		state.industries = [];
		payload.forEach(industry => {
			delete industry.image;
			state.industries.push(industry);
		});
	},
	[_industries.FETCH_INDUSTRY_MAJORS_BY_FIELD](state,payload) {
		state.industryMajorsByField = [];
		payload[0].forEach(major => {
			major.majorId = major.hegisCode;
			delete major.hegisCode;
			state.industryMajorsByField.push(major);
		});
	},
	[_industries.RESET_INDUSTRY_STATE](state){
		state.industries = [];
		state.industryMajorsByField = [];
	},
	[_industries.TOGGLE_INDUSTRY_FORM_WAS_SUBMITTED](state, payload){
		state.industryFormWasSubmitted = true;
	},
	[_industries.TOGGLE_INDUSTRY_EDUCATION_LEVEL](state,payload){
		state.industryEducationLevel = payload;
	}
};
