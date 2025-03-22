import ProductGrid from './components/product-grid.tsx';

export default function ShopPage() {
  return (
    <>
      <div className="h-full flex-1 space-y-4 overflow-y-auto p-4 pt-6 md:p-8">
        <ProductGrid />
      </div>
    </>
  );
}
