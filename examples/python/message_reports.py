import os
import sys

from kalourmade_arkesel import ArkeselClient

ids = sys.argv[1:] or sys.exit("Usage: python message_reports.py <id> [id...]")
client = ArkeselClient(os.environ["ARKESEL_API_KEY"])

reports = client.sms.message_reports(ids)

for id, report in reports.items():
    if report.error is not None:
        print(f"{id} — error: {report.error}")
    else:
        print(f"{id} — {report.message} ({report.status.value})")
