import { toast } from "vue3-toastify";
import 'vue3-toastify/dist/index.css';

export function successToast(msg) {
    toast.success(msg, {
      position: toast.POSITION.TOP_RIGHT,
    });
  }
  
  export function errorToast(msg) {
    toast.error(msg || 'Something went wrong!', {
      position: toast.POSITION.TOP_RIGHT,
    });
  }
  
  export function warnToast(msg) {
    toast.warning(msg || 'Something went wrong!', {
      position: toast.POSITION.TOP_RIGHT,
    });
  }