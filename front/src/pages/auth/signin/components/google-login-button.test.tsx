import { beforeEach, describe, expect, it, vi } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, fireEvent, render, screen } from '@testing-library/react';
import GoogleLoginButton from './google-login-button';
import { useGoogleLogin } from '@react-oauth/google';
// import {apiLogin} from "@/lib/api.ts";

vi.mock('@react-oauth/google', () => ({
  useGoogleLogin: vi.fn()
}));

vi.mock('@/lib/api', () => ({
  apiLogin: vi.fn()
}));

describe('GoogleLoginButton', () => {
  beforeEach(() => {
    cleanup();
  });

  it('renders the button', () => {
    render(<GoogleLoginButton />);
    expect(
      screen.getByRole('button', { name: /google login/i })
    ).toBeInTheDocument();
  });

  it('calls useGoogleLogin with correct options when the button is clicked', () => {
    const mockGoogleLogin = vi.fn();
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-expect-error
    (useGoogleLogin as vi.Mock).mockReturnValue(mockGoogleLogin);

    render(<GoogleLoginButton />);
    const button = screen.getByRole('button', { name: /google login/i });
    fireEvent.click(button);

    expect(useGoogleLogin).toHaveBeenCalledWith({
      onSuccess: expect.any(Function),
      flow: 'auth-code',
      scope: 'openid profile email'
    });

    expect(mockGoogleLogin).toHaveBeenCalled();
  });

  it('button triggers the google login function', () => {
    const mockLoginFunction = vi.fn();
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-expect-error
    (useGoogleLogin as vi.Mock).mockReturnValue(mockLoginFunction);

    render(<GoogleLoginButton />);
    const button = screen.getByRole('button', { name: /google login/i });
    fireEvent.click(button);

    expect(mockLoginFunction).toHaveBeenCalled();
  });
});
