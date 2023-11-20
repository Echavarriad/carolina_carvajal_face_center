<script>
import {
  required,
  minLength,
  sameAs,
  helpers,
  email,
  numeric,
  alphaNum,
} from "vuelidate/lib/validators";
import "bootstrap-vue/dist/bootstrap-vue.css";
import getLogin from "../functions/get-login";
const isMobileNumber = helpers.regex("numeric", /^3[0-9]{9}$/);
const isValidPassword = helpers.regex(
  "alphaNum",
  /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]/
);
export default {
  props: ["redirect_login", "content"],
  data() {
    return {
      name: null,
      lastname: null,
      document: null,
      type_document: null,
      email: null,
      mobile: null,
      password: null,
      confirm_password: null,
      state: null,
      city: null,
      address: null,
      complement: null,
      check: false,
    };
  },
  validations: {
    document: { required },
    name: { required },
    lastname: { required },
    email: { required, email },
    mobile: { required },
    password: { required, minLength: minLength(6), isValidPassword },
    confirm_password: { sameAsPassword: sameAs("password") },
  },
  mounted() {
    this.$root.changeStatusGif(false);
  },
  methods: {
    createAccount() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return false;
      }
      if (!this.check) {
        this.$toast.error(this.content.confirm_check_policy);
        return false;
      }

      this.$root.changeStatusGif(true);
      let data = {
        document: this.document,
        name: this.name,
        lastname: this.lastname,
        mobile: this.mobile,
        email: this.email,
        password: this.password,
      };
      axios.post(`ajax/create-account`, data).then((response) => {
        this.$root.changeStatusGif(false);
        if (response.data.status == "save") {
          this.$swal({
            icon: "success",
            title: response.data.message,
            confirmButtonText: this.content.acept,
          }).then((result) => {
            if (result.value) {
              this.getLogin();
            }
          });
        } else {
          this.$swal({
            icon: "error",
            title: response.data.message,
            showCancelButton: false,
            confirmButtonText: this.content.acept,
          });
        }
      });
    },
    async getLogin() {
      this.$root.changeStatusGif(true);
      const { status, message } = await getLogin(
        this.email,
        this.password,
        this.redirect_login
      );
      if (!status) {
        this.$root.changeStatusGif(false);
        this.$swal({
          icon: "error",
          title: message,
          showCancelButton: false,
          confirmButtonText: this.content.acept,
        });
      }
    },
  },
};
</script>
