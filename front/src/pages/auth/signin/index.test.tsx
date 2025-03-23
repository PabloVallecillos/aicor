import { it, expect, describe, beforeEach } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, render, screen } from '@testing-library/react';
import SignInPage from '@/pages/auth/signin/index.tsx';
import { GoogleOAuthProvider } from '@react-oauth/google';

describe('SignInComponent', () => {
  beforeEach(() => {
    cleanup();
  });

  it('should have a google login button', () => {
    render(
      <GoogleOAuthProvider clientId="test-client-id">
        <SignInPage />
      </GoogleOAuthProvider>
    );
    expect(
      screen.getByRole('button', { name: /google login/i })
    ).toBeInTheDocument();
  });
});
