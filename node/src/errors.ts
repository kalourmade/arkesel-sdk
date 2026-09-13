export class ArkeselError extends Error {
  constructor(message: string) {
    super(message);
    this.name = new.target.name;
    Object.setPrototypeOf(this, new.target.prototype);
  }
}

export class AuthenticationError extends ArkeselError {}
export class InsufficientBalanceError extends ArkeselError {}
export class ValidationError extends ArkeselError {}
export class NetworkError extends ArkeselError {}

export class ApiError extends ArkeselError {
  constructor(
    public readonly statusCode: number,
    public readonly rawBody: string,
    message: string
  ) {
    super(message);
  }
}
