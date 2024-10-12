import { ref } from 'vue';
import axiosInst from '@/components/axiosins';

export default function useUpdate() {
  const upderrormsg = ref('')
  const updsuccessmsg = ref('')

  const update = async (url, data, callback) => {   

    try {
      const res = await axiosInst.put(url, data);     
      upderrormsg.value = '';
      updsuccessmsg.value = 'Se actualizó con éxito';
      if (callback) {
        callback();
      }
      return res.data;
    } catch (error) {
      updsuccessmsg.value = ''
      upderrormsg.value = error.response.data.message;
    }
  };

  return {
    update,
    upderrormsg,
    updsuccessmsg
  };
}
