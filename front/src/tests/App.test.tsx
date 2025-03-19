import { it, expect, describe } from "vitest";

import "@testing-library/jest-dom/vitest";
import { render, screen } from "@testing-library/react";
import App from "../App.tsx";

describe("App", () => {
  it("should render the \"Vite + React\" heading", () => {
    render(<App />);

    const heading = screen.getByText(/Vite \+ React/i);
    expect(heading).toBeInTheDocument();
  });
});