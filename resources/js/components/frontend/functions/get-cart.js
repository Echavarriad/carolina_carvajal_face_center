const getCart= async (email, password, redirect_login) => {

    const resp= await axios.post('ajax/get-cart');

    return {status: true, cartData: resp.data};
}

export default getCart;