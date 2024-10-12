// icons
import {
  DashboardOutlined,
  FolderAddOutlined,
  TagsOutlined,
  AppleOutlined,
  TeamOutlined ,
  ShoppingCartOutlined,
  BarChartOutlined,
  ShoppingOutlined
  

} from '@ant-design/icons-vue';

export interface menu {
  header?: string;
  title?: string;
  icon?: object;
  to?: string;
  divider?: boolean;
  chip?: string;
  chipColor?: string;
  chipVariant?: string;
  chipIcon?: string;
  children?: menu[];
  disabled?: boolean;
  type?: string;
  subCaption?: string;
}

const sidebarItem: menu[] = [
  { header: 'Navigation' },
  {
    title: 'Dashboard',
    icon: BarChartOutlined,
    to: '/dashboard'
  },
  
  { header: 'Utilities' },
  {
    title: 'Vender',
    icon:   ShoppingCartOutlined,
    to: '/Vender'
  },
  {
    title: 'Productos',
    icon:   ShoppingOutlined,
    to: '/productos'
  },
  {
    title: 'Categorias',
    icon:  TagsOutlined,
    to: '/categorias'
  },
   {
    title: 'Marcas',
    icon:   AppleOutlined , 
    to: '/marcas'
  },
  {
    title: 'Proveedores',
    icon:   TeamOutlined,
    to: '/proveedores'
  },
  
];

export default sidebarItem;
