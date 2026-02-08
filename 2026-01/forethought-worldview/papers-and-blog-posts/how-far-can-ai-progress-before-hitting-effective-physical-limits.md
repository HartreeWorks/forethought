---
title: "How Far Can AI Progress Before Hitting Effective Physical Limits?"
url: https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits
---

# How Far Can AI Progress Before Hitting Effective Physical Limits?

[Tom Davidson](https://www.forethought.org/people/tom-davidson) [Rose Hadshar](https://www.forethought.org/people/rose-hadshar) [William MacAskill](https://www.forethought.org/people/william-macaskill)

## Authors

17th March 2025


_This is a rough research note – we’re sharing it for feedback and to spark discussion. We’re less confident in its methods and conclusions._

Once AI systems can themselves design and build even more capable AI systems, this will create feedback loops where AI builds better AI which builds better AI. More specifically, we can think about three important feedback loops:

- A _software_ feedback loop, where AI develops better software. Software includes AI training algorithms, post-training enhancements, ways to leverage runtime compute (like o1), synthetic data, and any other non-compute improvements.

- A _chip technology_ feedback loop, where AI designs better computer chips. Chip technology includes all the cognitive research and design work done by NVIDIA, TSMC, ASML, and other semiconductor companies.

- A _chip production_ feedback loop, where AI and robots build more computer chips.


If one or several of these feedback loops cause AI progress to become very fast, we could call this an “ [intelligence explosion](https://www.forethought.org/research/three-types-of-intelligence-explosion)”.

In simplified models of intelligence explosions, progress goes to [infinity](https://www.openphilanthropy.org/research/could-advanced-ai-drive-explosive-economic-growth/#id-618-models-predicting-explosive-growth-have-implausible-implications). But in reality, this is of course implausible. We will eventually hit effective limits on all of software, chip technology and chip production improvements, and so progress from each of these sources will eventually plateau. And progress could slow long before effective physical limits are reached, because of diminishing marginal returns, regulation, or other factors.

In this essay, we set aside human constraints and focus on effective physical limits, as these are easier to analyse.

How far can AI progress before hitting effective physical limits? If we are already close to the limits, then the potential capability increases will be smaller. But if the limits are very high, then ultimate capability levels (and the maximum pace of progress to reach them) could be extreme.

We can operationalize the distance to physical limits in terms of effective compute.

We argue that, setting aside human constraints:

- [**Software**](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#software-progress) could likely increase effective compute by **~12 OOMs**, possibly more.

- [**Chip technology**](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#chip-technology-progress) could likely increase effective compute by **~2 OOMs** within the [current paradigm](https://epoch.ai/blog/limits-to-the-energy-efficiency-of-cmos-microprocessors), and by a total of **~6 OOMs** if technology approaches [Landauer’s limit](https://en.wikipedia.org/wiki/Landauer%27s_principle) (a physical constraint on the energy efficiency of computation). [Reversible computing](https://en.wikipedia.org/wiki/Reversible_computing) could conceivably go further still.

- [**Chip production**](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#chip-production-progress) could scale by **~5 OOMs** using earth-based energy capture, and by a further **~9 OOMs** if space-based solar could capture _all_ the energy emitted by the sun.


![3D cube diagram showing three dimensions of effective compute improvement: better software (vertical axis), better chip technology (horizontal axis), and more chip production (depth axis).](https://images.ctfassets.net/4owxfjx3z3if/23fLSSbvDpSUG4OVxuFAnR/fe8520bb5ccec626e87748252e5cfc02/Amrit_Graphs_-31.png?w=3840&q=75&fm=webp)

There are three ways to increase effective compute in our framework: better software, better chip technology, and more chip production.


## Software progress

Software could likely increase effective compute by **~12 OOMs,** possibly more.

Focusing just on the room for improvement for training efficiency, how much better could software get before it reaches effective physical limits?

- Human lifetime learning takes [1e24 FLOP](https://docs.google.com/document/d/1IJ6Sr-gPeXdSJugFulwIpvavc0atjHGM82QjIfUSBGQ/edit?tab=t.0#heading=h.87mp14r9lgsj). Assuming the relative gap between GPT-4 and GPT-6 will be the same as the gap between GPT-2 and GPT-4, GPT-6 will be trained with 1e29 FLOP.[1](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-1)

- If each GPT-6 computation were as useful as a human computation, then GPT-6 would be ~5 OOMs less training efficient than human lifetime learning, suggesting at least 5 more OOMs to physical limits.[2](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-2) Of course, GPT-6 might be more or less useful per computation as a human brain, but we’ll use this as a rough estimate.

- But the human brain is not the physical limit of training efficiency. Firstly, the data used for human learning could be greatly improved upon: the brain is severely undertrained, humans spend only a small fraction of their time on focussed academic learning,[3](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-3) and data quality could be much higher. Secondly, brain algorithms could be improved upon. Brains have to satisfy biological physical constraints, and humans have to coordinate via language. Combined with the significant variation between humans and the fact that evolution is a blind search process, it seems that there is significant room for improvement. [Davidson and Houlden (2025)](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#gap-from-human-learning-to-effective-limits) estimate that these factors add another 4-10 OOMs, so 9-15 OOMs total. The midpoint of this range is ~12 OOMs.


There will likely be other kinds of gain besides training efficiency, which would increase this estimate somewhat.

## Chip technology progress

Chip technology could likely increase effective compute by **~2 OOMs** within the current paradigm, and by a total of **~6 OOMs** if technology approaches Landauer’s limit. [Reversible computing](https://en.wikipedia.org/wiki/Reversible_computing) could go further still.

We’ll measure this in units of FLOP/J – how many floating point operations chips can do per joule of energy. (This is consistent with our calculation of the limits to chip production below, where we consider the scale at which we could increase the energy used by chips.)

[Epoch estimates](https://epoch.ai/blog/limits-to-the-energy-efficiency-of-cmos-microprocessors) that within the current paradigm, there is room for a ~200X improvement, which is ~2 OOMs.[4](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-4)

But beyond the current paradigm, there could be room for further improvements. [Landauer’s limit](https://en.wikipedia.org/wiki/Landauer%27s_principle) implies that it’s not physically possible to do more than 3e20 bit erasures per Joule. We want to convert this limit to FLOP/J, so we need to estimate the number of bit erasures per FLOP. As a rough ballpark estimate for the limits of non-reversible computing, if we use 8-bit precision numbers, we might have slightly more than 8 bit erasures per FLOP – 8 because a FLOP takes in two 8-bit numbers but outputs only one; and a little more for some intermediate calculations along the way. Let’s round it to 10 bit erasures per FLOP. That implies we could achieve 3e19 FLOP/J.

Today you can get [~1e13 FLOP/J](https://epoch.ai/blog/limits-to-the-energy-efficiency-of-cmos-microprocessors), which implies ~6.5 OOMs of improvement before reaching effective physical limits.

If [reversible computing](https://en.wikipedia.org/wiki/Reversible_computing) is possible, the ceiling could be higher still: Landauer’s limit only applies to irreversible logical operations, so reversible computing could surpass this limit.

## Chip production progress

Chip production could scale by **~5 OOMs** using earth-based energy capture, and by a further **~9 OOMs** if space-based solar could capture _all_ the energy emitted by the sun:

- Data centres currently consume [1-1.5%](https://www.iea.org/energy-system/buildings/data-centres-and-data-transmission-networks) of global electricity, and [10-20%](https://www.epri.com/research/products/3002028905) of data centres are used for AI, so AI chips currently consume 0.1-0.3% of global electricity.[5](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-5) This share could perhaps increase 100X, to 10-30%.[6](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-6)

- Earth-based solar power could increase the current economy’s energy capture by around 3000X.[7](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-7)

- Taken together this is 300,000X, which is ~5 OOMs.

- Space-based solar power within this solar system could increase total energy capture a billionfold more again,[8](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fn-8) which is a further ~9 OOMs.


## Combined progress

These are our estimated limits for each of the three feedback loops:

![Bar chart showing physical limits for AI feedback loops: Software (~12 OOMs), Chip technology (~6 OOMs), and Chip production (~5 OOMs Earth-based, ~14 OOMs with all solar energy).](https://images.ctfassets.net/4owxfjx3z3if/3257oFRjgjGHoFIkcoiuAU/8dbfa6db5ae2e37e17f1d6780f4915a0/physical-limits-feedback-loops-comparison.png?w=3840&q=75&fm=webp)

Our estimates of the total room for improvement for each feedback loop before hitting effective physical limits. The limits for software and chip technology might be higher.


What does this mean for how far an intelligence explosion could go? [Elsewhere](https://www.forethought.org/research/three-types-of-intelligence-explosion), we argue that these feedback loops could lead to three particularly plausible kinds of intelligence explosion (IE):

- A _software_ IE, where AI-driven software improvements alone are sufficient for rapid and accelerating AI progress.

- An _AI-technology_ IE, where AI-driven improvements in both software and chip technology are needed, but AI-driven improvements in chip production are not.

- A _full-stack_ IE, where AI-driven improvements in all of software, chip technology and chip production are needed.


To see how far these three intelligence explosions could go, we can simply add up the limits of the feedback loops to estimate the limits for each IE:

- **The software IE** could increase effective compute by ~12 OOMs, possibly more.

- **The AI-technology IE** could increase effective compute by ~18 OOMs or more.

- **The full-stack IE** could increase effective compute by ~23 OOMs using earth-based energy, or ~32 OOMs using all solar energy.


![Bar chart showing cumulative physical limits for intelligence explosions: Software IE (~12 OOMs), AI-technology IE (~18 OOMs), Full-stack IE (~23 OOMs Earth-based energy, ~32 OOMs with all solar energy).](https://images.ctfassets.net/4owxfjx3z3if/4eGIlDknfVLEdjHROJlTRb/7e9e551df03c7f4671c117df0b797775/intelligence-explosion-physical-limits-comparison.png?w=3840&q=75&fm=webp)

Our estimates of the total room for improvement for each intelligence explosion before hitting effective physical limits. All the limits might be higher.


1

[Epoch estimates](https://epoch.ai/data/notable-ai-models) that GPT-4 was trained with 2e25 FLOP, 10,000X more compute than GPT-2. In which case, GPT-6 would be trained with 2e29 FLOP. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-1)

2

Thanks to Carl Shulman for this argument. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-2)

3

In our context, we’re concerned with AI’s ability to do R&D, so we can take human learning that improves their later R&D capabilities as a baseline to compare to. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-3)

4

Epoch’s calculation is for 16-bit precision. Lower precision would allow further gains. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-4)

5

Working [here](https://docs.google.com/spreadsheets/d/1Hkk-Gvv5JMMRgMW6yS8fpQlaMRbWMgUKjvX1cdW_r1s/edit?gid=304983116#gid=304983116). The amount of energy spent on producing advanced AI chips is much lower (see estimates [here](https://docs.google.com/spreadsheets/d/1Hkk-Gvv5JMMRgMW6yS8fpQlaMRbWMgUKjvX1cdW_r1s/edit?gid=0#gid=0)), so the bulk of the energy to consider is the energy from actually running chips. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-5)

6

This is implausible if energy supply remains static, but as per the following point, energy supply could expand dramatically. So spending 20-60% of energy on AI data centres wouldn’t mean cutting off the power to other things we use energy for today - it would mean using most of any new energy supplies to power data centres. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-6)

7

Workings [here](https://docs.google.com/spreadsheets/d/1Hkk-Gvv5JMMRgMW6yS8fpQlaMRbWMgUKjvX1cdW_r1s/edit?gid=185396384#gid=185396384). Fusion power could increase that further but only at the cost of dramatically increasing Earth’s temperature. [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-7)

8

Workings [here](https://docs.google.com/spreadsheets/d/1Hkk-Gvv5JMMRgMW6yS8fpQlaMRbWMgUKjvX1cdW_r1s/edit?gid=185396384#gid=185396384). [↩](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits#user-content-fnref-8)