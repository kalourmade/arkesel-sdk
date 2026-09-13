import type { Transport } from './http.js';
import { throwIfError } from './errorMapping.js';

export interface Contact {
  phoneNumber: string;
  firstName?: string;
  lastName?: string;
  company?: string;
  emailAddress?: string;
  userName?: string;
}

export class GroupsResource {
  constructor(private readonly transport: Transport) {}

  async create(groupName: string): Promise<void> {
    const { status, body } = await this.transport.request(
      'POST',
      '/api/v2/contacts/groups',
      undefined,
      { group_name: groupName }
    );
    throwIfError(status, body);
  }

  async addContacts(groupName: string, contacts: Contact[]): Promise<void> {
    const wireContacts = contacts.map((c) => ({
      phone_number: c.phoneNumber,
      ...(c.firstName !== undefined && { first_name: c.firstName }),
      ...(c.lastName !== undefined && { last_name: c.lastName }),
      ...(c.company !== undefined && { company: c.company }),
      ...(c.emailAddress !== undefined && { email_address: c.emailAddress }),
      ...(c.userName !== undefined && { user_name: c.userName }),
    }));

    const { status, body } = await this.transport.request(
      'POST',
      '/api/v2/contacts',
      undefined,
      { group_name: groupName, contacts: wireContacts }
    );
    throwIfError(status, body);
  }
}
