# DVWA Source Code Review

Team collaboration to find and document security vulnerabilities in DVWA source code.

## Team Members
- na7na7
- abdo elkafrawy 
- nada medhat
- somm3a
- haneen

## Workflow
1. Each person creates their own branch: `git checkout -b yourname-branch`
2. Work on your assigned vulnerabilities in the `Findings/` folder
3. Commit changes: `git add . && git commit -m "description"`
4. Push to GitHub: `git push origin yourname-branch`
5. Merge to main when ready

## Folder Structure
- `DVWA-Source/` - Original DVWA code (READ ONLY - do not modify)
- `Findings/` - Our vulnerability reports
- `Documentation/` - Setup notes and guides

## Vulnerability Areas to Review
- SQL Injection
- Cross-Site Scripting (XSS)
- Cross-Site Request Forgery (CSRF)
- File Upload Vulnerabilities
- Command Injection
- Brute Force Attacks
- Security Misconfigurations
