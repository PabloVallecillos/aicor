import { Button } from '@/components/ui/button';
import { useGoogleLogin } from '@react-oauth/google';
import { useAuth } from '@/hooks/use-auth.tsx';
import { apiLogin } from '@/lib/api.ts';
import { useRouter } from '@/routes/hooks';
import { toast } from '@/components/ui/use-toast.ts';

export default function GoogleLoginButton() {
  const { login } = useAuth();
  const router = useRouter();

  const googleLogin = useGoogleLogin({
    onSuccess: async (tokenResponse) => {
      const res = await apiLogin(tokenResponse.access_token);
      if ('access_token' in res) {
        login(String(res.access_token));
        router.push('/');
      } else {
        toast({
          title: 'Error',
          description: 'Authentication failed'
        });
      }
    },
  });

  return (
    <Button
      onClick={googleLogin}
      className="ml-auto w-full uppercase"
      type="button"
    >
      google login
    </Button>
  );
}
