//MAJORS MUTATIONS
import _majors from '../../mutation-types/majors';

export default {
    [_majors.FETCH_MAJORS](state, payload){
        payload.forEach((major) => {
            major.majorId = major.hegis_code;
            delete major.hegis_code;
            state.majors.push(major);
        });
    },

    [_majors.FETCH_FIELD_OF_STUDIES](state, payload){
        payload.forEach((fieldOfStudy) => {
            fieldOfStudy.discipline = fieldOfStudy.name;
            delete fieldOfStudy.name;
            state.fieldOfStudy.push(fieldOfStudy);
        });
    },

    [_majors.FETCH_UPDATED_MAJORS_BY_FIELD](state, payload) {
        let index = payload.cardIndex;
        state.majorCards[index].majorsByField = [];
        payload[0].forEach((major) => {
            major.majorId = major.hegisCode;
            delete major.hegisCode;
            state.majorCards[index].majorsByField.push(major);
        });
    },

    [_majors.FETCH_MAJOR_DATA](state, payload) {
        let index = payload.cardIndex;
        state.majorCards[index].majorData = payload;
    },

    [_majors.RESET_MAJOR_SELECTIONS](state) {
        state.majors = [];
    },

    [_majors.FETCH_UNIVERSITIES](state, payload) {
        payload.forEach((university) => {
            university.name = university.university_name;
            delete university.university_name;
            state.universities.push(university);
        });
    },

    [_majors.FETCH_INDUSTRY_IMAGES](state, payload) {
        let index = payload.cardIndex;
        state.majorCards[index].industries = payload;
    },

    [_majors.TOGGLE_EDUCATION_LEVEL](state, payload) {
        let index = payload.cardIndex;
        state.majorCards[index].educationLevel = payload.educationLevel;
    },

    [_majors.TOGGLE_FORM_WAS_SUBMITTED](state, payload) {
        let index = payload;
        state.majorCards[index].formWasSubmitted = true;
    },

    [_majors.ADD_MAJOR_CARD](state) {
        state.majorCards.push({
            majorsByField: [],
            educationLevel: 'allDegrees',
            industries: [],
            majorData: [],
            formWasSubmitted: false,
        });
    }

}