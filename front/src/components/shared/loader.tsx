import { LoaderCircle } from 'lucide-react';

export default function Loader() {
  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <LoaderCircle className="h-12 w-12 animate-spin text-primary" />
    </div>
  );
}
