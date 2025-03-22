import { Button } from '@/components/ui/button';

export default function UserAuthForm() {
  return (
    <>
      <Button
        disabled={false}
        className="ml-auto w-full uppercase"
        type="submit"
      >
        google login
      </Button>
    </>
  );
}
