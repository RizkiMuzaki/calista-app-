# Changelog - CALISTA

## [2026-04-21] - Stability & Finalization Phase
### Added
- Logout logic with confirmation dialog in `ParentalSettingsScreen`.
- Reward condition validation in `ShopController@claimReward` (Backend).
- "Coming Soon" snackbar for Forgotten Password in `LoginScreen`.

### Fixed
- Linter warnings in `ProgressController` and `ChildController` (Added type-hinting).
- Hardcoded `childId` in `GameSelectionScreen` and `HomeScreen` navigation.
- Dynamic profile refresh in `ParentalSettingsScreen`.

### Technical Improvements
- Better session management using dynamic `childId` across the app.
- Stronger server-side validation for in-game item rewards.
