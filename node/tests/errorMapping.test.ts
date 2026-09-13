import { describe, expect, it } from 'vitest';
import { throwIfError } from '../src/errorMapping';
import { ApiError, AuthenticationError, InsufficientBalanceError, ValidationError } from '../src/errors';

describe('throwIfError', () => {
  it('does not throw for status < 400', () => {
    expect(() => throwIfError(200, '{}')).not.toThrow();
  });

  it('throws InsufficientBalanceError for 402', () => {
    expect(() => throwIfError(402, '{}')).toThrow(InsufficientBalanceError);
  });

  it('throws AuthenticationError for 403', () => {
    expect(() => throwIfError(403, '{}')).toThrow(AuthenticationError);
  });

  it('throws ValidationError for 422', () => {
    expect(() => throwIfError(422, '{}')).toThrow(ValidationError);
  });

  it('throws ApiError with status and body for other 4xx/5xx', () => {
    try {
      throwIfError(500, '{"status":"failed"}');
      expect.fail('Expected ApiError');
    } catch (e) {
      expect(e).toBeInstanceOf(ApiError);
      expect((e as ApiError).statusCode).toBe(500);
      expect((e as ApiError).rawBody).toBe('{"status":"failed"}');
    }
  });
});
