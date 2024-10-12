import { format } from 'date-fns';

export default {
  install(app) {
    app.config.globalProperties.$dateForm = (date, formatStr = 'dd/MM/yyyy') => {
      return format(new Date(date), formatStr);
    };
  },
};