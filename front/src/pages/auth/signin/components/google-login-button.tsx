import { Button } from '@/components/ui/button';
import { useGoogleLogin } from '@react-oauth/google';
// import { useAuth } from "@/hooks/use-auth.tsx";
import { apiLogin } from '@/lib/api.ts';

export default function GoogleLoginButton() {
  // const { login } = useAuth();

  const googleLogin = useGoogleLogin({
    onSuccess: async ({ code }) => {
      console.log(code);
      try {
        const response = await apiLogin(code);
        console.log(response);
      } catch (error) {
        console.error('Error', error);
      }
    },
    flow: 'auth-code',
    scope: 'openid profile email'
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
