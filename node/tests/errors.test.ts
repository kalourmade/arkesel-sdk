import { describe, expect, it } from 'vitest';
import {
  ApiError,
  ArkeselError,
  AuthenticationError,
  InsufficientBalanceError,
  NetworkError,
  ValidationError,
} from '../src/errors';

describe('error hierarchy', () => {
  it('all error types extend ArkeselError', () => {
    expect(new AuthenticationError('x')).toBeInstanceOf(ArkeselError);
    expect(new InsufficientBalanceError('x')).toBeInstanceOf(ArkeselError);
    expect(new ValidationError('x')).toBeInstanceOf(ArkeselError);
    expect(new NetworkError('x')).toBeInstanceOf(ArkeselError);
    expect(new ApiError(500, 'body', 'x')).toBeInstanceOf(ArkeselError);
  });

  it('ApiError carries status and raw body', () => {
    const error = new ApiError(500, '{"status":"failed"}', 'Arkesel API error (status 500)');

    expect(error.statusCode).toBe(500);
    expect(error.rawBody).toBe('{"status":"failed"}');
    expect(error.message).toBe('Arkesel API error (status 500)');
  });
});
