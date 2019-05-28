const fetchFreDataAPI = (payload, success, error) => {
    window.axios.get(`api/pfre/${payload.education}/${payload.major}/${payload.earnings}/${payload.financialAid}`)
    .then(
        response => success(response.data.pfre),
    ).catch(
        failure=>{
            error(failure)
        }
    );
}

export default {
    fetchFreDataAPI
}