import { it, expect, describe, beforeEach } from 'vitest';
import '@testing-library/jest-dom/vitest';
import { cleanup, render, screen } from '@testing-library/react';
import NotFoundPage from '@/pages/not-found/index';
import { MemoryRouter } from 'react-router-dom';

describe('NotFoundPage', () => {
  beforeEach(() => {
    cleanup();
  });

  it('should display a 404 error message', () => {
    render(
      <MemoryRouter>
        <NotFoundPage />
      </MemoryRouter>
    );
    expect(screen.getByText(/404/i)).toBeInTheDocument();
  });
});
