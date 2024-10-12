import { ref } from 'vue';
import axiosInst from '@/components/axiosins';

export default function useRegister() {
  
  const regerrormsg = ref('')
  const regsuccessmsg = ref('')


  const register = async (url, data, callback) => {

    try {
      await axiosInst.post(url, data)
      regerrormsg.value = ''
      if (callback) {
        callback()
      }
      return regsuccessmsg.value = 'Registro éxitoso'
    } catch (error) {
      regerrormsg.value = error.response.data.message
      regsuccessmsg.value = ''
    }
  };

  return {
    register,
    regsuccessmsg,
    regerrormsg
  };
}
