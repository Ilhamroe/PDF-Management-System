## Description

Brief description of what this PR does.

Fixes #(issue number)

## Type of Change

Please check the relevant option:

- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update
- [ ] Code refactoring
- [ ] Performance improvement
- [ ] Tests

## Changes Made

Detailed description of changes:

- Added/Modified/Removed X
- Updated Y to Z
- Fixed issue with W

## Testing

Describe the tests you ran to verify your changes:

- [ ] Unit tests
- [ ] Feature tests
- [ ] Manual testing
- [ ] Postman collection testing

**Test Configuration:**

- PHP Version:
- Database:
- OS:

**Test Results:**

```
Paste test output here
```

## API Changes

If this PR introduces API changes, document them:

**New Endpoints:**

```
POST /api/example
```

**Modified Endpoints:**

```
GET /api/example - Added new query parameter
```

**Breaking Changes:**

```
Removed field 'x' from response
```

## Database Changes

- [ ] No database changes
- [ ] New migration included
- [ ] Migration tested (up and down)
- [ ] Data migration script provided (if needed)

**Migration Summary:**

```
Brief description of schema changes
```

## Documentation

- [ ] Updated README.md
- [ ] Updated API documentation
- [ ] Updated Postman collection
- [ ] Updated CHANGELOG.md
- [ ] Added/updated code comments
- [ ] No documentation needed

## Screenshots (if applicable)

Add screenshots to demonstrate changes:

**Before:**
[Screenshot]

**After:**
[Screenshot]

## Performance Impact

- [ ] No performance impact
- [ ] Performance improved
- [ ] Performance may be affected (explain below)

**Details:**

```
Explain performance considerations
```

## Security Considerations

- [ ] No security impact
- [ ] Security reviewed
- [ ] Potential security implications (explain below)

**Details:**

```
Explain security considerations
```

## Checklist

Before submitting, ensure you have:

**Code Quality:**

- [ ] Code follows project style guidelines (run `./vendor/bin/pint`)
- [ ] Self-review completed
- [ ] Code is commented, particularly in complex areas
- [ ] No console errors or warnings

**Testing:**

- [ ] All existing tests pass (`php artisan test`)
- [ ] New tests added for new features
- [ ] Tested locally end-to-end
- [ ] Tested edge cases and error scenarios

**Documentation:**

- [ ] Documentation updated (if needed)
- [ ] Postman collection updated (if API changed)
- [ ] CHANGELOG.md updated

**Dependencies:**

- [ ] No new dependencies added
- [ ] New dependencies justified and documented
- [ ] `composer.lock` / `package-lock.json` committed

**Git:**

- [ ] Commit messages are clear and descriptive
- [ ] Branch is up-to-date with main
- [ ] No merge conflicts
- [ ] Commits are atomic and well-organized

## Additional Notes

Any additional information that reviewers should know:

- Dependencies on other PRs
- Known limitations
- Future improvements planned
- Questions for reviewers

## Reviewer Guidelines

**For reviewers, please check:**

- [ ] Code quality and style
- [ ] Test coverage is adequate
- [ ] Documentation is clear
- [ ] No security issues
- [ ] Performance is acceptable
- [ ] API changes are backward compatible (or breaking changes are justified)

---

**Deployment Notes:**

Special considerations for deployment (if any):

```
e.g., run migrations, clear cache, restart queue workers
```
