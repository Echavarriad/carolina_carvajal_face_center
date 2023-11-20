const getLogin= async (email, password, redirect_login) => {

    const resp= await axios.post('ajax/login', { email, password });    
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = resp.data.csrfToken;
    if(!resp.data.status){
        return {status: false, message: resp.data.message};
    }

    setTimeout(function(){ location.href = redirect_login; }, 1500);

    return {status: true, message: ''};
}

export default getLogin;