import { ApiError, AuthenticationError, InsufficientBalanceError, ValidationError } from './errors.js';

export function throwIfError(status: number, rawBody: string): void {
  if (status < 400) {
    return;
  }

  const message = `Arkesel API error (status ${status})`;

  if (status === 402) throw new InsufficientBalanceError(message);
  if (status === 403) throw new AuthenticationError(message);
  if (status === 422) throw new ValidationError(message);
  throw new ApiError(status, rawBody, message);
}
