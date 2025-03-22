import { it, expect, describe, beforeEach } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, render, screen } from '@testing-library/react';
import PurchaseItem from '@/pages/purchases/components/purchase-item';
import type { Purchase } from '@/types';

describe('PurchaseItem', () => {
  beforeEach(() => {
    cleanup();
  });

  it('should render the PurchaseItem component', () => {
    const purchase: Purchase = {
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
    };

    render(<PurchaseItem purchase={purchase} />);
    expect(screen.getByText('ORD-1239')).toBeInTheDocument();
  });

  it('should display the date and total price correctly', () => {
    const purchase: Purchase = {
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
    };

    render(<PurchaseItem purchase={purchase} />);
    expect(screen.getByText('$59.99')).toBeInTheDocument();
    expect(screen.getByText(/May 28, 2023/i)).toBeInTheDocument();
  });

  it('should correctly display the number of products', () => {
    const purchase: Purchase = {
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
    };

    render(<PurchaseItem purchase={purchase} />);
    expect(screen.getByText('2 products')).toBeInTheDocument();
  });

  it('should use singular form when there is only one product', () => {
    const purchase: Purchase = {
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
    };

    render(<PurchaseItem purchase={purchase} />);
    expect(screen.getByText('1 product')).toBeInTheDocument();
  });
});
