import { NavItem, Product, type Purchase } from '@/types';
import { Package, ShoppingBag } from 'lucide-react';

export const navItems: NavItem[] = [
  {
    title: 'Start',
    href: '/',
    icon: ShoppingBag
  },
  {
    title: 'My Purchases',
    href: '/purchases',
    icon: Package
  }
];

export const mockProducts: Product[] = [
  {
    id: '1',
    name: 'Wireless Headphones',
    price: 129.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Headphones with noise cancellation and great sound quality'
  },
  {
    id: '2',
    name: 'Premium Smartwatch',
    price: 199.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Smartwatch with multiple functions and health monitoring'
  },
  {
    id: '3',
    name: '4K Digital Camera',
    price: 349.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'High-resolution camera for professional photos and videos'
  },
  {
    id: '4',
    name: 'Ultrathin Laptop',
    price: 899.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Powerful laptop with a next-gen processor'
  },
  {
    id: '5',
    name: 'Bluetooth Speaker',
    price: 79.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Portable speaker with surround sound and long battery life'
  },
  {
    id: '6',
    name: 'HD Tablet',
    price: 249.99,
    image: '/placeholder.svg?height=300&width=300',
    description:
      'Tablet with high-definition display and exceptional performance'
  },
  {
    id: '7',
    name: 'Mechanical Keyboard',
    price: 89.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Gaming keyboard with RGB backlighting and mechanical switches'
  },
  {
    id: '8',
    name: 'Curved Monitor',
    price: 329.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Curved gaming monitor with a high refresh rate'
  },
  {
    id: '9',
    name: 'Gaming Chair',
    price: 249.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Ergonomic chair with lumbar support and height adjustment'
  },
  {
    id: '10',
    name: 'Wireless Charger',
    price: 49.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Fast charger compatible with multiple devices'
  },
  {
    id: '11',
    name: 'Anti-theft Backpack',
    price: 69.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Backpack with hidden compartments and integrated USB charging'
  },
  {
    id: '12',
    name: 'Wireless Mouse',
    price: 39.99,
    image: '/placeholder.svg?height=300&width=300',
    description:
      'Ergonomic mouse with Bluetooth connectivity and adjustable DPI'
  },
  {
    id: '13',
    name: 'Smart LED Lamp',
    price: 29.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Lamp with remote control and adjustable color temperature'
  },
  {
    id: '14',
    name: '1TB SSD',
    price: 129.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Ultrafast solid-state drive for secure storage'
  },
  {
    id: '15',
    name: 'Full HD Webcam',
    price: 99.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Webcam with built-in microphone and autofocus'
  },
  {
    id: '16',
    name: 'Game Controller',
    price: 59.99,
    image: '/placeholder.svg?height=300&width=300',
    description: 'Wireless controller with vibration and customizable buttons'
  }
];

export const mockPurchases: Purchase[] = [
  {
    id: 'ORD-1234',
    date: new Date('2023-11-15T10:30:00'),
    status: 'Delivered',
    total: 329.97,
    items: [
      {
        product: {
          id: '1',
          name: 'Wireless Headphones',
          price: 129.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Headphones with noise cancellation'
        },
        quantity: 1
      },
      {
        product: {
          id: '5',
          name: 'Bluetooth Speaker',
          price: 79.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Portable speaker with surround sound'
        },
        quantity: 2
      }
    ]
  },
  {
    id: 'ORD-1235',
    date: new Date('2023-10-28T14:45:00'),
    status: 'Delivered',
    total: 899.99,
    items: [
      {
        product: {
          id: '4',
          name: 'Ultrathin Laptop',
          price: 899.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Powerful laptop with a next-gen processor'
        },
        quantity: 1
      }
    ]
  },
  {
    id: 'ORD-1236',
    date: new Date('2023-12-05T09:15:00'),
    status: 'In Process',
    total: 429.98,
    items: [
      {
        product: {
          id: '3',
          name: '4K Digital Camera',
          price: 349.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'High-resolution camera'
        },
        quantity: 1
      },
      {
        product: {
          id: '7',
          name: 'Mechanical Keyboard',
          price: 89.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Gaming keyboard with RGB backlighting'
        },
        quantity: 1
      }
    ]
  },
  {
    id: 'ORD-1237',
    date: new Date('2023-09-10T11:00:00'),
    status: 'Delivered',
    total: 549.99,
    items: [
      {
        product: {
          id: '8',
          name: 'Sport Smartwatch',
          price: 199.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Smartwatch with activity monitoring'
        },
        quantity: 2
      }
    ]
  },
  {
    id: 'ORD-1238',
    date: new Date('2023-08-22T13:15:00'),
    status: 'In Process',
    total: 199.98,
    items: [
      {
        product: {
          id: '2',
          name: 'Ergonomic Mouse',
          price: 49.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Ergonomic mouse with 6 buttons'
        },
        quantity: 2
      }
    ]
  },
  {
    id: 'ORD-1239',
    date: new Date('2023-07-14T10:20:00'),
    status: 'Delivered',
    total: 699.99,
    items: [
      {
        product: {
          id: '6',
          name: '4K Monitor',
          price: 699.99,
          image: '/placeholder.svg?height=300&width=300',
          description: '32-inch UHD 4K monitor'
        },
        quantity: 1
      }
    ]
  },
  {
    id: 'ORD-1240',
    date: new Date('2023-05-28T16:30:00'),
    status: 'Delivered',
    total: 59.99,
    items: [
      {
        product: {
          id: '9',
          name: 'Wireless Charger',
          price: 59.99,
          image: '/placeholder.svg?height=300&width=300',
          description: '15W fast wireless charger'
        },
        quantity: 1
      }
    ]
  },
  {
    id: 'ORD-1241',
    date: new Date('2023-04-15T10:50:00'),
    status: 'In Process',
    total: 249.97,
    items: [
      {
        product: {
          id: '10',
          name: 'Bluetooth Keyboard',
          price: 149.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Compact keyboard with Bluetooth connection'
        },
        quantity: 1
      },
      {
        product: {
          id: '11',
          name: 'Gaming Mouse',
          price: 99.98,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Gaming mouse with high precision'
        },
        quantity: 1
      }
    ]
  },
  {
    id: 'ORD-1242',
    date: new Date('2023-03-10T17:40:00'),
    status: 'Delivered',
    total: 799.99,
    items: [
      {
        product: {
          id: '12',
          name: '4K Security Camera',
          price: 799.99,
          image: '/placeholder.svg?height=300&width=300',
          description: 'Security camera with night vision and 4K recording'
        },
        quantity: 1
      }
    ]
  }
];
