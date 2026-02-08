---
title: "Once AI Research is Automated, Will AI Progress Accelerate?"
url: https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate
---

# Once AI Research is Automated, Will AI Progress Accelerate?

[Tom Davidson](https://www.forethought.org/people/tom-davidson) [Rose Hadshar](https://www.forethought.org/people/rose-hadshar) [William MacAskill](https://www.forethought.org/people/william-macaskill)

## Authors

17th March 2025


_This is a rough research note – we’re sharing it for feedback and to spark discussion. We’re less confident in its methods and conclusions._

Today, AI progress is driven by humans, who write software and design and produce AI chips. But in the future, more and more human work will become automated. Eventually, AI progress will be driven by AI, not humans.

We can imagine a graph which plots capabilities over time, and shows the transition to AI-driven progress as well as the era of AI-driven progress:

![Graph showing AI capability progression from human-driven (green line) through transition period (orange) to AI-driven progress (blue S-curve). Purple labels mark five key questions about suddenness, speed-up, acceleration, peak rate, and plateau height.](https://images.ctfassets.net/4owxfjx3z3if/2cA9q9rK1E9OxGjc2vPgrl/434e7a092a81e856e69659b2de98897a/Amrit_Graphs_-14.png?w=3840&q=75&fm=webp)


In the graph AI progress is initially driven by human efforts (green line). Then over time AI increasingly automates the work for improving AI (orange line). Eventually, AI does almost all the work for improving AI (blue line).

The graph raises several different factors which are relevant to understanding the landscape of the transition to AI-driven progress:

1. **[Suddenness](https://www.forethought.org/research/how-suddenly-will-ai-accelerate-the-pace-of-ai-progress):** how many months or years does it take to transition from human-driven progress to AI-driven progress?

2. **[Initial speed-up](https://www.forethought.org/research/h/research/how-suddenly-will-ai-accelerate-the-pace-of-ai-progress#initial-speed-up-from-ai-automation):** how much faster is the _initial_ period of AI-driven progress, compared to the final period of human-driven progress?

3. **Acceleration:** after the transition, will AI-driven progress _accelerate_ over time?

4. **[Peak growth rate](https://www.forethought.org/research/three-types-of-intelligence-explosion#appendix-how-fast-could-ai-progress-eventually-become):** how fast could AI progress eventually become?

5. **[Plateau](https://www.forethought.org/research/how-far-can-ai-progress-before-hitting-effective-physical-limits):** how far can AI progress before hitting effective physical limits?


In this post, we’re going to discuss (iii): once AI has fully automated the work to improve AI, will AI progress accelerate over time?

This is an important question, because if progress accelerates, we could end up with much larger and faster increases in AI capabilities.

First, we explain the [conditions under which progress will accelerate](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#under-what-conditions-does-progress-accelerate) in general terms.

Then we evaluate [whether these conditions hold](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#will-ai-progress-accelerate) for three separate feedback loops via which AI will improve AI:

- A _software_ feedback loop, where AI develops better software. Software includes AI training algorithms, post-training enhancements, ways to leverage runtime compute (like o1), synthetic data, and any other non-compute improvements.

- A _chip technology_ feedback loop, where AI designs better computer chips. Chip technology includes all the cognitive research and design work done by NVIDIA, TSMC, ASML, and other semiconductor companies.

- A _chip production_ feedback loop, where AI and robots build more computer chips.


We argue that, putting aside any human actions to slow AI progress:

- **The software feedback loop** _by itself_ might well be enough to sustain accelerating progress (~50%).

- **The chip technology feedback loop** _by itself_ is probably enough to sustain accelerating progress (~65%).

- **The chip production feedback loop** _by itself_ is very likely enough to sustain accelerating progress (~80%).


We conclude by arguing that [overall AI progress is even more likely to accelerate](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#will-intelligence-explosions-accelerate), as it can involve all three feedback loops.

(This piece gives qualitative explanations only, and is based on forthcoming mathematical work by Basil Halperin and Thomas Houlden.)

## Under what conditions does progress accelerate?

AI improving AI is not the only example of a positive feedback loop. Before discussing AI in particular, we’ll consider the general conditions under which a positive feedback loop leads to accelerating growth.

Let’s imagine a simple system that takes inputs and produces outputs. For example, we can think of the global economy taking inputs of human labour and physical capital, and outputting goods and services.

If the outputs can be used to create more inputs, then there’s a positive feedback loop. (For example, goods and services are used to build more physical capital.)

![Simple feedback loop diagram showing a large circle labeled "Inputs" (orange striped) connected by an arrow to a smaller square labeled "Outputs" (purple striped), with a curved feedback arrow returning from outputs back to inputs.](https://images.ctfassets.net/4owxfjx3z3if/6K4vOO5snnU2b89f4PByAe/255d3dcdbc5581b6053cbf5065432f29/Amrit_Graphs_-26.png?w=3840&q=75&fm=webp)


The existence of a positive feedback loop, however, doesn’t mean that you get accelerating progress. For acceleration, each doubling of inputs must take less time than the previous doubling - even though the volume of inputs is twice as large.[1](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-1)

The key issue is: **when you double the (cumulative) inputs,[2](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-2) how many times do the outputs double?**

If outputs double exactly once, then you have constant exponential growth – no acceleration.

![Two-panel diagram showing feedback loop at different times. Earlier time: 200 inputs produce 20 outputs with 1% growth rate. Later time: 400 inputs (doubled) produce 40 outputs (also doubled) maintaining same 1% growth rate.](https://images.ctfassets.net/4owxfjx3z3if/7wEFWlVKKgpPPU6R8M7u9u/8f20bde09e56b38ee27ac86ed5e1d1bf/Amrit_Graphs_-27.png?w=3840&q=75&fm=webp)

At the earlier time, 200 units of input produced 20 units of output. 2 of these units of output are reinvested as inputs (a 10% reinvestment rate), so inputs have a growth rate of 1%. To grow at the same rate once inputs have doubled, you need to produce double the outputs (assuming a constant reinvestment rate).


And if outputs _more than_ double, you get acceleration: at the later time you have twice as many inputs (so growing them by 1% takes twice as much effort) but you have _more than_ twice as many outputs.

![Three-panel diagram showing accelerating feedback loop. Starting with 200 inputs producing 20 outputs (1% growth), then 400 inputs producing 80 outputs (2% growth), then 800 inputs producing 320 outputs (4% growth rate).](https://images.ctfassets.net/4owxfjx3z3if/4EWpsjXoBTnABzUm7tKKqq/65a85452d61e975cff09256764d04767/Amrit_Graphs_-28.png?w=3840&q=75&fm=webp)

When inputs double, outputs more than double. As a result, the growth rate increases.


In [some growth models](https://epoch.ai/blog/do-the-returns-to-software-rnd-point-towards-a-singularity), the number of times outputs double when cumulative inputs double is called _r_.[3](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-3) If r>1, outputs double more than once and you get accelerating growth. If r is below one, outputs less than double when you double cumulative inputs, so growth doesn’t accelerate.

For example, over the long-run course of history, doubling the number of humans has (on average) led to _more_ than a doubling of output. (With twice as many humans, you can have twice as many people farming. But those people can _also_ be coming up with new ideas for better farming methods, so that the amount of food produced - and humans that can be supported in future generations - more than doubles.) And this meant that (on average) the next doubling of humans happened more quickly, as r was greater than 1.

| Year | Human population | Years to double |
| --- | --- | --- |
| 0 CE | ~200m | 1100 |
| 1100 | ~400m | 660 |
| 1760 | ~800m | 140 |
| 1900 | ~1.6bn | 60 |
| 1960 | ~3.2bn | 40 |
| 2000 | ~6.4bn | - |

Each doubling of the [human population](https://ourworldindata.org/grapher/population?time=1400..latest&country=~OWID_WRL) has taken less time, even though each increase was twice as large.

In domains where doubling inputs _does_ lead to more than a doubling of outputs, the returns to doubling inputs will eventually start to diminish more steeply, r will drop below 1, and growth will stop accelerating — for example once the system approaches some sort of physical limit. So, in practice, accelerating growth is always just one part of an s-curve:

![S-curve showing log output over time with three phases labeled: "Growth accelerates" (steep rising curve), "Growth slows" (curve flattening), and "Growth stops" (flat plateau at top).](https://images.ctfassets.net/4owxfjx3z3if/6DORM75UKtEQj7RQ3QTzlL/7b5e97ab35e127404dcf389c9c4a5c4c/Amrit_Graphs_-29.png?w=3840&q=75&fm=webp)


## Will AI progress accelerate?

For AI progress, we’re thinking about the same accelerating growth dynamics, but with cognitive rather than economic inputs and outputs.

Currently, humans do almost all the cognitive labour for improving AI, and so there isn’t a strong feedback loop of better AI → more cognitive labour for improving AI → better AI. But if AI replaced humans at the task of improving AI, the resulting feedback loop could lead to accelerating AI progress.

![Two-panel comparison showing cognitive labor transition. Left: "Human cognitive labour" produces "Better AI" with no feedback loop. Right: "AI cognitive labour" produces "Better AI" with feedback arrow showing self-reinforcement.](https://images.ctfassets.net/4owxfjx3z3if/5fghWBq28XQcgrncPKNXq7/e0257aaed2d6b490260a10dd1b9c123a/Amrit_Graphs_-30.png?w=3840&q=75&fm=webp)


We can define “accelerating AI progress” as “each increment of capability advancement (e.g. GPT-2 → GPT-3 or GPT-3 → GPT-4) happens more quickly than the last”.

As in the general case of accelerating growth, the key question is how many times the outputs double for each doubling of AI inputs:[4](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-4) i.e. what is r? If r>1, AI progress accelerates. If r<1, it doesn’t.

We can get empirical evidence on r for each of the three feedback loops, and use this to guess the likelihood of acceleration.

We argue that:

- For the **software feedback loop**, r is around 1, and possibly slightly higher.

- For the **chip technology feedback loop**, r is comfortably above 1.

- For the **chip production feedback loop**, r is robustly above 1.


Note that in this analysis we focus on _technological_ likelihood only, and ignore any human bottlenecks like regulation or cultural constraints which might limit the pace of acceleration. The all-things-considered likelihood of acceleration will be lower.

### Software feedback loop

The question we care about is, when we double the human researcher population (i.e. double cognitive inputs), how many times does software double?[5](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-5) If the answer is >1, then progress is accelerating.

The most relevant data is on efficiency improvements:

- **Training efficiency of ImageNet.** The most relevant estimate is Epoch’s [analysis](https://arxiv.org/abs/2405.10494) of Imagenet training efficiency, which estimates r = ~1.4.

- **Other data on efficiency gains.** Epoch also [analysed](https://arxiv.org/abs/2405.10494) runtime efficiency in other domains:


  - Computer chess: r = 0.8

  - Problem solvers: r = 3.5

  - Linear programming: r = 1.1


But we don’t just care about efficiency gains – getting the same capabilities for less compute – we also care about improving capabilities – the ability to do tasks we previously couldn’t do.[6](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-6) This increases the estimate significantly. After considering this and downwards adjustment factors like limited compute for running experiments, [Davidson and Houlden (2025)](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#returns-to-software-rd-) estimate r = 1.2, with a range of 0.4 to 3.6.

[Eth and Davidson](https://docs.google.com/document/d/1rw1pTbLi2brrEP0DcsZMAVhlKp6TKGKNUSFRkkdP_hs/edit?tab=t.0#heading=h.9vgai87ecxln) review the evidence for software improvements, and argue that, after accounting for compute-experiment bottlenecks, a reasonable range for r today is 0.5 - 2.

**We think it is ~50% likely that the software feedback loop drives accelerating progress,** absent human bottlenecks\*\*.\*\*

### Chip technology feedback loop

Again we can ask the question, when we double the human researcher population, how many times does chip technology double?

- Evidence from Davidson’s [compute-centric framework report](https://www.forethought.org/research/will-ai-r-and-d-automation-cause-a-software-intelligence-explosion) suggests that recently r = ~5 when considering all inputs to hardware R&D.[7](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-7) If we assume that human cognitive labour is 60% of all hardware R&D inputs, then r = ~3.

- As with software, doubling chip technology is actually better than doubling the human researcher population (as you don’t just double the quality of your hardware, you consequently train a model with higher capabilities). This might approximately double r, to ~6.[8](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-8)

- We should adjust downwards as r will be falling over time (as the low-hanging fruit is picked), perhaps to r = ~4.

- Experts argue that we are approaching fundamental limits to the current paradigm.[9](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-9) We can make a further downward adjustment in deference to these experts, but r still seems likely to be comfortably above 1.


**We think it is likely (very roughly ~65%) that the chip technology feedback loop drives accelerating progress**, absent human bottlenecks.

### Chip production feedback loop

Because software and (our definition of) chip technology are virtual, better AI straightforwardly leads to better software and chip technology. But chip production also involves physical labour, so there is a key additional step in the feedback loop: robots (and other machines and infrastructure) which can fully automate the process of building chip factories.

With robots, a doubling of inputs would straightforwardly result in a doubling of outputs. That’s because if you have twice as many robots, they can _build_ twice as many. If robots also improve robot technology at all, which seems likely,[10](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-10) then output would more than double for each doubling of input.

On the other hand, doubling inputs might _less_ than double outputs if scarce natural resources take more and more work to extract, but historically when raw materials have become scarce this has been more than compensated for by innovation.[11](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-11)

(This is simply the argument given for accelerating economic growth – that once you fully automate labour the economy displays increasing returns to scale. See [Erdil and Besiroglu (2023)](https://arxiv.org/abs/2309.11690) and [Davidson (2021)](https://www.openphilanthropy.org/research/could-advanced-ai-drive-explosive-economic-growth/).)

**We think it is highly likely (very roughly ~80%) that the chip production feedback loop drives accelerating progress,** absent human bottlenecks.

## Will intelligence explosions accelerate?

What does this mean for whether an intelligence explosion would accelerate? [Elsewhere](https://www.forethought.org/research/three-types-of-intelligence-explosion), we argue that these feedback loops could lead to three particularly plausible kinds of intelligence explosion (IE):

- A _software_ IE, where AI-driven software improvements alone are sufficient for rapid and accelerating AI progress.

- An _AI-technology_ IE, where AI-driven improvements in both software and chip technology are needed, but AI-driven improvements in chip production are not.

- A _full-stack_ IE, where AI-driven improvements in all of software, chip technology and chip production are needed.


When thinking about how these feedback loops combine to create intelligence explosions, we can simply add up the r values for each feedback loop involved.[12](https://www.forethought.org/research/once-ai-research-is-automated-will-ai-progress-accelerate#user-content-fn-12) If the total r is greater than 1, then progress will accelerate.

This means that it’s more likely that the intelligence explosions accelerate, than that the feedback loops in isolation do.

Another important point here is that there’s some correlation between the r values:

- If ‘extra compute’ doesn’t translate to extra intelligence, that weakens both the chip technology and chip production feedback loops.

- If extra intelligence doesn’t actually help with research (e.g. because humans are already close to hitting non-cognitive bottlenecks in many domains), that weakens all of the feedback loops.


So lower r values for one feedback loop is correlated with lower r in others. This makes the extremes of very fast acceleration or no acceleration at all more plausible.

Overall, we think that:

- **A software IE might well accelerate over time,** because the software feedback loop _by itself_ might well be enough to sustain accelerating progress (~50%).

- **An AI-technology IE would likely accelerate.** The chip technology feedback loop _by itself_ is probably enough to sustain accelerating progress (~65%). This means the _combination_ of the software and chip technology feedback loops are likely jointly strong enough to drive accelerating progress (~75%).

- **A full-stack IE is highly likely to accelerate.** It’s likely that the chip production feedback loop _by itself_ can sustain accelerating progress (~80%). So in combination with the other feedback loops it is highly likely that an industrial IE would accelerate (~90%).


_Thanks to Max Dalton, Oscar Delaney, Daniel Eth, Fin Moorhouse and the governing explosive growth seminar group for helpful comments._